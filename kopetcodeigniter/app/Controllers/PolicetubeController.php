<?php

namespace App\Controllers;

use App\Controllers\AdminController;

class PolicetubeController extends AdminController
{
    // MENGAMBIL DATA DARI TABEL PUSAT MEDSOS
    protected $tableLinks = 'smart_report_medsos';
    protected $tableDaily = 'smart_report_policetube_stats';

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->ensureDatabaseExists();
    }

    public function index()
    {
        checkPermission('add_post');
        helper(['form', 'url', 'filesystem']);
        $pager = \Config\Services::pager();

        $data['title'] = 'Laporan PoliceTube';
        $db = \Config\Database::connect();

        // --- 1. DATA HARIAN (TAB DAILY) ---
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $data['selectedDate'] = $date;

        $builder = $db->table($this->tableLinks . ' as l');
        $builder->select('l.*, u.username'); 
        $builder->join('users as u', 'u.id = l.user_id', 'left'); 
        
        // PERBAIKAN: Ambil 'PoliceTube' DAN 'Lainnya' (Agar link berita umum juga masuk)
        $builder->whereIn('l.platform', ['PoliceTube', 'Lainnya']); 
        $builder->where('DATE(l.created_at)', $date);
        
        // Sorting: Polresta > Polres > Polsek
        $builder->orderBy("CASE 
            WHEN u.username LIKE '%POLRESTA%' THEN 0 
            WHEN u.username LIKE '%POLRES%' THEN 1 
            WHEN u.username LIKE '%POLSEK%' THEN 2
            ELSE 3 
        END", 'ASC');
        $builder->orderBy('l.created_at', 'ASC');

        // Pagination
        $page = (int) ($this->request->getGet('page_daily') ?? 1);
        $perPage = 10;
        $totalRows = $builder->countAllResults(false);
        $rawLinksDaily = $builder->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();
        
        $data['pager'] = $pager->makeLinks($page, $perPage, $totalRows, 'default_full', 0, 'daily');

        $processedDailyLinks = [];
        $no = ($page - 1) * $perPage + 1;
        foreach($rawLinksDaily as $link) {
            $link['no_urut'] = $no++;
            // Jika platform Lainnya, anggap sebagai 'Web/Berita', jika PoliceTube anggap 'Video'
            $link['category'] = ($link['platform'] == 'Lainnya') ? 'Web/Portal' : 'Video';
            
            // Nama Satker
            $satkerName = !empty($link['username']) ? $link['username'] : 'USER';
            $link['satker'] = strtoupper($satkerName);
            $processedDailyLinks[] = $link;
        }
        $data['dailyLinks'] = $processedDailyLinks;
        $data['totalDaily'] = $totalRows; 
        
        $data['dailyStats'] = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();

        // --- 2. DATA BULANAN ---
        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        $data['selectedMonth'] = (int)$month;
        $data['selectedYear']  = (int)$year;
        $data['monthlyTotal'] = $db->table($this->tableLinks)
            ->whereIn('platform', ['PoliceTube', 'Lainnya']) // Update Filter
            ->where('MONTH(created_at)', $month)->where('YEAR(created_at)', $year)
            ->countAllResults();

        // --- 3. DATA REKAPAN (RANGE) ---
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?: date('Y-m-d');
        $data['startDate'] = $startDate;
        $data['endDate']   = $endDate;

        if ($this->request->getGet('tab') == 'range') {
            $rangeLinks = $db->table($this->tableLinks . ' as l')
                ->select('l.*, u.username')
                ->join('users as u', 'u.id = l.user_id', 'left')
                ->whereIn('l.platform', ['PoliceTube', 'Lainnya']) // Update Filter
                ->where("DATE(l.created_at) >=", $startDate)
                ->where("DATE(l.created_at) <=", $endDate)
                ->orderBy("CASE WHEN u.username LIKE '%POLRESTA%' THEN 0 WHEN u.username LIKE '%POLRES%' THEN 1 ELSE 2 END", 'ASC')
                ->orderBy('l.created_at', 'ASC')
                ->get()->getResultArray();

            $processedRange = [];
            foreach($rangeLinks as $r) {
                $r['category'] = ($r['platform'] == 'Lainnya') ? 'Web/Portal' : 'Video';
                $r['satker'] = !empty($r['username']) ? strtoupper($r['username']) : 'USER';
                $processedRange[] = $r;
            }
            $data['rangeLinks'] = $processedRange;
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/smart_report/policetube', $data); 
        echo view('admin/includes/_footer');
    }

    // --- DOWNLOAD EXCEL ---
    public function exportExcel()
    {
        if (ob_get_length()) ob_end_clean();
        
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableLinks . ' as l');
        $builder->select('l.*, u.username');
        $builder->join('users as u', 'u.id = l.user_id', 'left');
        
        // Update Filter Export juga
        $builder->whereIn('l.platform', ['PoliceTube', 'Lainnya']); 

        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');
        $date      = $this->request->getGet('date');

        if ($startDate && $endDate) {
            $builder->where("DATE(l.created_at) >=", $startDate);
            $builder->where("DATE(l.created_at) <=", $endDate);
            $filenameDate = "Periode_" . str_replace('-', '', $startDate) . "_" . str_replace('-', '', $endDate);
            $titleLabel   = "PERIODE: " . date('d/m/Y', strtotime($startDate)) . " s.d " . date('d/m/Y', strtotime($endDate));
        } else {
            $date = $date ?: date('Y-m-d');
            $builder->where('DATE(l.created_at)', $date);
            $filenameDate = "Harian_" . date('Ymd', strtotime($date));
            $titleLabel   = "TANGGAL: " . date('d F Y', strtotime($date));
        }
        
        $builder->orderBy("CASE WHEN u.username LIKE '%POLRESTA%' THEN 0 WHEN u.username LIKE '%POLRES%' THEN 1 ELSE 2 END", 'ASC');
        $builder->orderBy('l.created_at', 'ASC');
        
        $rawLinks = $builder->get()->getResultArray();
        $processedLinks = [];
        $no = 1;
        foreach($rawLinks as $l) {
            $l['no'] = $no++;
            $l['category'] = ($l['platform'] == 'Lainnya') ? 'Web/Portal' : 'Video';
            $satkerName = !empty($l['username']) ? $l['username'] : 'USER';
            $l['satker'] = strtoupper($satkerName);
            $l['tanggal'] = date('d/m/Y', strtotime($l['created_at'])); 
            $processedLinks[] = $l;
        }
        
        $data['links'] = $processedLinks;
        $data['title_label'] = $titleLabel;
        $data['date_indo'] = $titleLabel; 
        
        $filename = "Laporan_PoliceTube_" . $filenameDate . ".xls";
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo view('admin/smart_report/policetube_export_excel', $data);
        exit;
    }

    public function save() { return redirect()->back(); }
    public function saveDailyStats() { checkPermission('add_post'); return redirect()->back(); }

    private function ensureDatabaseExists() {
        $db = \Config\Database::connect(); $forge = \Config\Database::forge();
        if ($db->tableExists($this->tableLinks)) {
            if (!$db->fieldExists('user_id', $this->tableLinks)) {
                $forge->addColumn($this->tableLinks, ['user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id']]);
            }
        }
        if (!$db->tableExists($this->tableDaily)) {
            $forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'date'=>['type'=>'DATE'],'views'=>['type'=>'INT','default'=>0],'likes'=>['type'=>'INT','default'=>0],'comments'=>['type'=>'INT','default'=>0],'shares'=>['type'=>'INT','default'=>0],'total_engagement'=>['type'=>'INT','default'=>0],'cover_image'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],'bg_image'=>['type'=>'VARCHAR','constraint'=>255,'null'=>true],'content_images'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);
            $forge->addKey('id', true)->createTable($this->tableDaily, true);
        }
    }
}