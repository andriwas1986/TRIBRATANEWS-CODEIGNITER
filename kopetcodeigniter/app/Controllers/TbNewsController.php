<?php

namespace App\Controllers;

use App\Models\TbNewsModel;

class TbNewsController extends AdminController
{
    protected $tbNewsModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['text', 'url', 'filesystem']); 
        $this->tbNewsModel = new TbNewsModel();
    }

    public function index()
    {
        checkPermission('add_post');

        $date = inputGet('date') ?: date('Y-m-d');
        $month = (int)(inputGet('month') ?: date('m')); 
        $year = inputGet('year') ?: date('Y');

        // Ambil Data dari Model
        $dailyStats = $this->tbNewsModel->getDailyStats($date);
        $monthlyRecap = $this->tbNewsModel->getMonthlyRecap($month, $year);
        $monthlyLinks = $this->tbNewsModel->getMonthlyLinks($month, $year);
        
        // Generate WA Text (Format Baru: Link Flat List & Total Count)
        $waText = $this->generateWaText($dailyStats, $date);

        $data['title'] = 'APP Tb News Dashboard';
        $data['dailyStats'] = $dailyStats;
        $data['waText'] = $waText;
        $data['monthlyRecap'] = $monthlyRecap;
        $data['monthlyLinks'] = $monthlyLinks;
        $data['selectedDate'] = $date;
        $data['selectedMonth'] = $month;
        $data['selectedYear'] = $year;
        $data['panelSettings'] = panelSettings(); 
        
        // Data Chart
        $data['chartLabels'] = !empty($dailyStats) ? json_encode(array_column($dailyStats, 'username')) : '[]';
        $data['chartData'] = !empty($dailyStats) ? json_encode(array_column($dailyStats, 'total_posts')) : '[]';

        echo view('admin/includes/_header', $data);
        echo view('admin/tbnews/index', $data);
        echo view('admin/includes/_footer');
    }

    public function exportExcel()
    {
        checkPermission('add_post');
        $month = (int)(inputGet('month') ?: date('m'));
        $year = inputGet('year') ?: date('Y');

        $data['monthlyRecap'] = $this->tbNewsModel->getMonthlyRecap($month, $year);
        $data['monthlyLinks'] = $this->tbNewsModel->getMonthlyLinks($month, $year);
        $data['selectedMonth'] = $month;
        $data['selectedYear'] = $year;

        $filename = "Rekap_TbNews_" . $month . "_" . $year . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo view('admin/tbnews/export_excel', $data);
    }

    private function generateWaText($stats, $date)
    {
        $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        $d = date('d', strtotime($date));
        $m = date('m', strtotime($date));
        $y = date('Y', strtotime($date));
        $namaBulan = isset($months[$m]) ? $months[$m] : '';
        $tanggalIndo = $d . ' ' . $namaBulan . ' ' . $y;
        
        // 1. Kumpulkan Link Terlebih Dahulu (Looping)
        // Kita butuh ini dulu untuk menghitung $totalSemua sebelum menulis Header
        $linkContent = "";
        $no = 1; 
        $totalSemua = 0;

        if (!empty($stats)) {
            foreach ($stats as $row) {
                if ($row['total_posts'] > 0) {
                    // Ambil link per user
                    $links = $this->tbNewsModel->getUserLinks($row['user_id'], $date);
                    foreach ($links as $link) {
                        $url = base_url($link['slug']); 
                        // Format: 1. https://...
                        $linkContent .= $no . ". " . $url . "\n";
                        
                        $no++;
                        $totalSemua++;
                    }
                }
            }
        }
        
        if ($totalSemua == 0) {
             $linkContent .= "- Belum ada data berita pada tanggal ini -\n";
        }

        // 2. Susun Header (Setelah tahu Totalnya)
        $text = "*POLRES MADIUN*\n";
        $text .= "Kepada : Yth. Kabid Humas Polda Jatim\n";
        $text .= "Tembusan : Kapolres Madiun\n\n";
        
        $text .= "DARI : KASI HUMAS POLRES MADIUN\n"; 
        $text .= "PERIHAL : LAPORAN VIRALISASI WEBSITE TRIBRATANEWS TANGGAL " . strtoupper($tanggalIndo) . "\n\n";
        
        $text .= "Assalamualaikum Wr. Wb.\n";
        // Masukkan variabel $totalSemua di sini
        $text .= "Ijin Melaporkan Komandan, rekapitulasi Pemberitaan Tribratanews Polres Madiun dan Polsek Jajaran sebanyak " . $totalSemua . " Link. Adapun rincian link berita sbb :\n\n";

        // 3. Gabungkan Isi Link
        $text .= $linkContent;

        // 4. Penutup
        $text .= "\nDemikian yang dapat kami laporkan.\n";
        $text .= "Wassalamualaikum Wr. Wb.\n";
       
        return $text;
    }
}