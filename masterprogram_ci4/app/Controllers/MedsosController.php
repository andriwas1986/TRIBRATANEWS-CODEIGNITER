<?php

namespace App\Controllers;

use App\Controllers\AdminController;
use Dompdf\Dompdf;
use Dompdf\Options;

// --- LIBRARY PHPRESENTATION LENGKAP ---
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Border; 
use PhpOffice\PhpPresentation\Shape\Drawing;
// --------------------------------------

class MedsosController extends AdminController
{
    protected $tableLinks = 'smart_report_medsos';
    protected $tableDaily = 'smart_report_daily_stats';
    protected $tableSettings = 'smart_report_settings';

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

        $data['title'] = 'Laporan Medsos';
        $db = \Config\Database::connect();

        $settingsQuery = $db->table($this->tableSettings)->get()->getResultArray();
        $settings = []; foreach($settingsQuery as $s) $settings[$s['meta_key']] = $s['meta_value'];
        $data['globalSettings'] = $settings;

        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $data['selectedDate'] = $date;

        $queryAll = $db->table($this->tableLinks)->where('DATE(created_at)', $date)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $groupedData = []; foreach ($queryAll as $row) { $groupedData[$row['platform']][] = $row; }
        $data['groupedLinks'] = $groupedData;

        $data['dailyStats'] = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();

        $postCounts = $db->table($this->tableLinks)->select('platform, COUNT(id) as total')->where('DATE(created_at)', $date)->groupBy('platform')->get()->getResultArray();
        $pMap = []; foreach($postCounts as $pc) $pMap[$pc['platform']] = $pc['total'];
        $data['postCounts'] = $pMap;
        $data['totalPost'] = array_sum($pMap);

        $page = (int) ($this->request->getGet('page_daily') ?? 1);
        $perPage = 10;
        $builder = $db->table($this->tableLinks)->where('DATE(created_at)', $date);
        $totalRows = $builder->countAllResults(false);
        $data['dailyLinks'] = $builder->orderBy('created_at', 'ASC')->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();
        $data['pager'] = $this->makeCustomPager($page, $perPage, $totalRows, adminUrl('smart-report/medsos'), 'daily');

        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        $data['selectedMonth'] = (int)$month;
        $data['selectedYear']  = (int)$year;
        $data['monthlySummary'] = $db->table($this->tableLinks)->select('platform, COUNT(id) as total')->where('MONTH(created_at)', $month)->where('YEAR(created_at)', $year)->groupBy('platform')->get()->getResultArray();
        $data['monthlyDetails'] = $db->table($this->tableLinks)->where('MONTH(created_at)', $month)->where('YEAR(created_at)', $year)->orderBy('created_at', 'ASC')->get()->getResultArray();

        echo view('admin/includes/_header', $data);
        echo view('admin/smart_report/medsos', $data);
        echo view('admin/includes/_footer');
    }

    private function makeCustomPager($currentPage, $perPage, $totalRows, $baseUrl, $tabName)
    {
        if ($totalRows <= $perPage) return '';
        $totalPages = ceil($totalRows / $perPage);
        $html = '<ul class="pagination pagination-sm no-margin pull-right">';
        $dateParam = $this->request->getGet('date') ? '&date=' . $this->request->getGet('date') : '';
        if ($currentPage > 1) { $prevUrl = $baseUrl . "?tab={$tabName}&page_daily=" . ($currentPage - 1) . $dateParam; $html .= '<li><a href="' . $prevUrl . '">&laquo;</a></li>'; } else { $html .= '<li class="disabled"><a href="#">&laquo;</a></li>'; }
        for ($i = 1; $i <= $totalPages; $i++) { $active = ($i == $currentPage) ? 'class="active"' : ''; $url = $baseUrl . "?tab={$tabName}&page_daily=" . $i . $dateParam; $html .= '<li ' . $active . '><a href="' . $url . '">' . $i . '</a></li>'; }
        if ($currentPage < $totalPages) { $nextUrl = $baseUrl . "?tab={$tabName}&page_daily=" . ($currentPage + 1) . $dateParam; $html .= '<li><a href="' . $nextUrl . '">&raquo;</a></li>'; } else { $html .= '<li class="disabled"><a href="#">&raquo;</a></li>'; }
        $html .= '</ul>';
        return $html;
    }

    public function save() {
        checkPermission('add_post');
        $rawInput = $this->request->getPost('raw_links');
        if (empty($rawInput)) return redirect()->back()->with('error', 'Input kosong!');
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $rawInput, $matches);
        $foundLinks = $matches[0];
        $db = \Config\Database::connect(); $builder = $db->table($this->tableLinks);
        foreach ($foundLinks as $url) {
            $url = trim($url);
            $platform = $this->detectPlatform($url);
            if ($builder->where('url', $url)->countAllResults() == 0) {
                $builder->insert(['platform' => $platform, 'url' => $url, 'created_at' => date('Y-m-d H:i:s')]);
            }
        }
        return redirect()->back()->with('success', "Disimpan: " . count($foundLinks) . " link.");
    }

    public function saveDailyEngagement() {
        checkPermission('add_post');
        $db = \Config\Database::connect();
        $date = $this->request->getPost('date');
        $stats = [ 'ig' => (int)$this->request->getPost('ig'), 'tw' => (int)$this->request->getPost('tw'), 'fb' => (int)$this->request->getPost('fb'), 'tt' => (int)$this->request->getPost('tt'), 'pt' => (int)$this->request->getPost('pt'), 'yt' => (int)$this->request->getPost('yt') ];
        $stats['total'] = array_sum($stats);
        $existing = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();
        $data = array_merge(['date' => $date, 'updated_at' => date('Y-m-d H:i:s')], $stats);
        if ($existing) { $db->table($this->tableDaily)->where('id', $existing['id'])->update($data); } else { $data['created_at'] = date('Y-m-d H:i:s'); $db->table($this->tableDaily)->insert($data); }
        return redirect()->to(adminUrl('smart-report/medsos?tab=daily&date=' . $date))->with('success', 'Angka Engagement Disimpan!');
    }

    public function saveDailyContent() {
        checkPermission('add_post');
        $db = \Config\Database::connect();
        $date = $this->request->getPost('date');
        $uploadPath = 'uploads/smart_report/content/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
        $existing = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();
        $contentImgs = $existing['content_images'] ?? '[]';
        $filesContent = $this->request->getFiles(); $newContentImgs = [];
        if (isset($filesContent['content_images'])) { foreach ($filesContent['content_images'] as $file) { if ($file->isValid() && !$file->hasMoved()) { $newName = $file->getRandomName(); $file->move($uploadPath, $newName); $newContentImgs[] = $uploadPath . $newName; } } }
        $currentArr = json_decode($contentImgs, true) ?: [];
        $finalArr = array_merge($currentArr, $newContentImgs);
        $finalJson = json_encode($finalArr);
        $data = ['date' => $date, 'content_images' => $finalJson, 'updated_at' => date('Y-m-d H:i:s')];
        if ($existing) { $db->table($this->tableDaily)->where('id', $existing['id'])->update($data); } else { $data['created_at'] = date('Y-m-d H:i:s'); $db->table($this->tableDaily)->insert($data); }
        return redirect()->to(adminUrl('smart-report/medsos?tab=daily&date=' . $date))->with('success', "Gambar berhasil diupload!");
    }

    public function deleteContentImage() {
        checkPermission('add_post');
        $db = \Config\Database::connect();
        $date = $this->request->getPost('date');
        $imageToDelete = $this->request->getPost('image_path');
        $existing = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();
        if ($existing) {
            $images = json_decode($existing['content_images'], true);
            if (($key = array_search($imageToDelete, $images)) !== false) { unset($images[$key]); if (file_exists(FCPATH . $imageToDelete)) { unlink(FCPATH . $imageToDelete); } }
            $finalJson = json_encode(array_values($images));
            $db->table($this->tableDaily)->where('id', $existing['id'])->update(['content_images' => $finalJson]);
        }
        return redirect()->to(adminUrl('smart-report/medsos?tab=daily&date=' . $date))->with('success', 'Gambar berhasil dihapus.');
    }

    public function saveGlobalSettings() {
        checkPermission('add_post');
        $db = \Config\Database::connect();
        $uploadPath = 'uploads/smart_report/settings/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        if ($this->request->getPost('delete_cover') == '1') $this->removeSettingFile('cover_image');
        if ($this->request->getPost('delete_bg') == '1') $this->removeSettingFile('bg_image');
        if ($this->request->getPost('delete_lampiran') == '1') $this->removeSettingFile('bg_lampiran');

        $polresName = $this->request->getPost('polres_name');
        if ($polresName) { $this->updateSetting('polres_name', strtoupper($polresName)); }

        $fileCover = $this->request->getFile('cover_image'); 
        if ($fileCover && $fileCover->isValid()) { 
            $fileCover->move($uploadPath, 'cover_global.png', true); 
            $this->updateSetting('cover_image', $uploadPath . 'cover_global.png'); 
        }

        $fileBg = $this->request->getFile('bg_image'); 
        if ($fileBg && $fileBg->isValid()) { 
            $fileBg->move($uploadPath, 'bg_global.png', true); 
            $this->updateSetting('bg_image', $uploadPath . 'bg_global.png'); 
        }

        $fileBgLamp = $this->request->getFile('bg_lampiran'); 
        if ($fileBgLamp && $fileBgLamp->isValid()) { 
            $fileBgLamp->move($uploadPath, 'bg_lampiran.png', true); 
            $this->updateSetting('bg_lampiran', $uploadPath . 'bg_lampiran.png'); 
        }

        return redirect()->to(adminUrl('smart-report/medsos?tab=settings'))->with('success', 'Pengaturan Tersimpan!');
    }

    private function removeSettingFile($key) {
        $db = \Config\Database::connect();
        $row = $db->table($this->tableSettings)->where('meta_key', $key)->get()->getRowArray();
        
        if ($row && !empty($row['meta_value'])) {
            $filePath = FCPATH . $row['meta_value'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $this->updateSetting($key, '');
        }
    }

    private function updateSetting($key, $val) {
        $db = \Config\Database::connect(); $builder = $db->table($this->tableSettings);
        if ($builder->where('meta_key', $key)->countAllResults() > 0) $builder->where('meta_key', $key)->update(['meta_value' => $val]); else $builder->insert(['meta_key' => $key, 'meta_value' => $val]);
    }

    // --- 1. PREVIEW DI BROWSER (Pakai file export_preview.php) ---
    public function viewReport() { 
        $data = $this->prepareReportData(); 
        echo view('admin/smart_report/export_preview', $data); 
    }
    
    // --- 2. DOWNLOAD PDF (Pakai file export_pdf.php) ---
    public function exportPDF() { 
        if (ob_get_length()) ob_end_clean();

        $data = $this->prepareReportData(); 
        
        // Panggil View yang dikhususkan untuk Dompdf
        $html = view('admin/smart_report/export_pdf', $data); 
        
        $options = new Options(); 
        $options->set('isRemoteEnabled', true); 
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options); 
        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render(); 
        
        $dompdf->stream("Laporan_Medsos_".$data['date_indo'].".pdf", ["Attachment" => false]); 
        
        exit();
    }

    // --- PPTX ENGINE (A4 PORTRAIT) ---
    public function exportPPTX()
    {
        if (ob_get_length()) ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);

        $db = \Config\Database::connect(); 
        $data = $this->prepareReportData();
        
        $stats = $data['stats']; 
        $global = $data['global']; 
        $siteName = $data['siteName'];
        $pMap = $data['pMap']; 
        $totalPost = $data['totalPost']; 
        
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $ts = strtotime($date);
        
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $monthsUpper = [1=>'JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
        
        $tanggalFull = $days[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $months[(int)date('m', $ts)] . ' ' . date('Y', $ts);
        $dateFile = date('j', $ts) . ' ' . $monthsUpper[(int)date('m', $ts)] . ' ' . date('Y', $ts);

        $cleanSiteName = preg_replace('/[^A-Za-z0-9 \-]/', '', $siteName);
        $filename = strtoupper(trim($cleanSiteName)) . " - LAPORAN VIRALISASI " . $dateFile . ".pptx";

        $objPHPPowerPoint = new PhpPresentation();
        $objPHPPowerPoint->getLayout()->setCX(794); 
        $objPHPPowerPoint->getLayout()->setCY(1123);
        $objPHPPowerPoint->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_A4, false); 

        // SLIDE 1: COVER
        $objPHPPowerPoint->removeSlideByIndex(0); 
        $slide1 = $objPHPPowerPoint->createSlide(); 
        if (!empty($global['cover_image']) && file_exists(FCPATH . $global['cover_image'])) {
            $shape = $slide1->createDrawingShape(); 
            $shape->setPath(FCPATH . $global['cover_image'])->setWidth(794)->setHeight(1123)->setOffsetX(0)->setOffsetY(0);
        } else {
            $bg = $slide1->createRichTextShape()->setHeight(1123)->setWidth(794); 
            $bg->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF003366');
        }
        $boxTitle = $slide1->createRichTextShape()->setHeight(200)->setWidth(700)->setOffsetX(47)->setOffsetY(400);
        $boxTitle->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $run1 = $boxTitle->createTextRun("LAPORAN VIRALISASI\n");
        $run1->getFont()->setBold(true)->setSize(32)->setColor(new Color('FFFFFF00'))->setName('Arial Black'); 
        $run2 = $boxTitle->createTextRun("PRODUK " . strtoupper($cleanSiteName));
        $run2->getFont()->setBold(true)->setSize(28)->setColor(new Color('FFFFFF00'))->setName('Arial Black'); 
        $boxBottom = $slide1->createRichTextShape()->setHeight(100)->setWidth(700)->setOffsetX(47)->setOffsetY(650);
        $boxBottom->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $run3 = $boxBottom->createTextRun(strtoupper($cleanSiteName) . "\n");
        $run3->getFont()->setBold(true)->setSize(24)->setColor(new Color('FFFFFF00'))->setName('Arial Black');
        $run4 = $boxBottom->createTextRun($tanggalFull);
        $run4->getFont()->setBold(true)->setSize(20)->setColor(new Color('FFFFFF00'))->setName('Arial Black');

        // SLIDE 2: STATISTIK (DIPERLEBAR)
        $slide2 = $objPHPPowerPoint->createSlide(); 
        $bgSlide2 = $slide2->createRichTextShape()->setHeight(1123)->setWidth(794);
        $bgSlide2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');

        // Header 1 (Tinggi 100px)
        $this->createSectionHeader($slide2, "JUMLAH POSTINGAN MEDIA SOSIAL", 50);
        
        $dataPost = [ 'IG' => $pMap['Instagram']??0, 'X' => $pMap['Twitter/X']??0, 'FB' => $pMap['Facebook']??0, 'TT' => $pMap['TikTok']??0, 'PT' => ($pMap['PoliceTube']??0)+($pMap['Lainnya']??0), 'YT' => $pMap['YouTube']??0 ];
        // Tabel 1 (Y=180, Lebar Kolom 100px)
        $this->createSpecialTable($slide2, "POSTINGAN", 180, $dataPost, $totalPost);

        // Header 2 (Tinggi 100px)
        $this->createSectionHeader($slide2, "JUMLAH ENGAGEMENT MEDIA SOSIAL", 500);
        
        $dataEng = [ 'IG' => $stats['ig']??0, 'TW' => $stats['tw']??0, 'FB' => $stats['fb']??0, 'TT' => $stats['tt']??0, 'PT' => $stats['pt']??0, 'YT' => $stats['yt']??0 ];
        // Tabel 2 (Y=630, Lebar Kolom 100px)
        $this->createSpecialTable($slide2, "ENGAGEMENT", 630, $dataEng, $stats['total']??0);


        // SLIDE 3 DST: LAMPIRAN
        if (!empty($stats['content_images'])) {
            $images = json_decode($stats['content_images'], true);
            if (is_array($images) && count($images) > 0) {
                $chunks = array_chunk($images, 9);
                $colW = 220; $rowH = 280; $gapX = 15; $gapY = 30; $startX = 45; $startY = 160; 
                $positions = [];
                for($r=0; $r<3; $r++) { for($c=0; $c<3; $c++) { $positions[] = ['x' => $startX + ($c * ($colW + $gapX)), 'y' => $startY + ($r * ($rowH + $gapY))]; } }

                foreach ($chunks as $chunk) {
                    $slide3 = $objPHPPowerPoint->createSlide();
                    if (!empty($global['bg_lampiran']) && file_exists(FCPATH . $global['bg_lampiran'])) {
                        $bg = $slide3->createDrawingShape(); $bg->setPath(FCPATH . $global['bg_lampiran'])->setWidth(794)->setHeight(1123)->setOffsetX(0)->setOffsetY(0);
                    } else { 
                        $this->addBackgroundToSlide($slide3, $global, 794, 1123); 
                    }
                    foreach ($chunk as $idx => $img) {
                        if (file_exists(FCPATH . $img) && filesize(FCPATH . $img) > 0) {
                            $size = @getimagesize(FCPATH . $img); 
                            if ($size && $size[0] > 0 && $size[1] > 0) {
                                $w = $size[0]; $h = $size[1]; $boxW = 220; $boxH = 280; $ratio = min($boxW / $w, $boxH / $h); 
                                $newW = $w * $ratio; $newH = $h * $ratio;
                                $offX = $positions[$idx]['x'] + ($boxW - $newW) / 2; $offY = $positions[$idx]['y'] + ($boxH - $newH) / 2;
                                $shape = $slide3->createDrawingShape(); $shape->setPath(FCPATH . $img)->setWidth($newW)->setHeight($newH)->setOffsetX($offX)->setOffsetY($offY);
                            }
                        }
                    }
                }
            }
        }

        // SLIDE 4 DST: LINKS (2 KOLOM, TABLE SHAPE - INVISIBLE BORDER)
        // Solusi "Anti Numpuk" -> Pakai Table Shape tapi bordernya di-hide.
        $links = $db->table($this->tableLinks)->where('DATE(created_at)', $date)->orderBy('platform', 'ASC')->get()->getResultArray();
        $linksByPlatform = []; foreach($links as $l) { $p = ($l['platform']=='Lainnya')?'PoliceTube':$l['platform']; $linksByPlatform[$p][] = $l['url']; }
        
        foreach($linksByPlatform as $plat => $urls) {
            // Kapasitas: 25 Baris per Kolom -> 50 Link per Slide
            $chunks = array_chunk($urls, 50); 
            $pageCount = 1;
            
            foreach($chunks as $pageLinks) {
                $slideLink = $objPHPPowerPoint->createSlide();
                $this->addBackgroundToSlide($slideLink, $global, 794, 1123);
                
                $startNo = ($pageCount-1)*50 + 1;
                
                $colLeft = array_slice($pageLinks, 0, 25);
                $colRight = array_slice($pageLinks, 25); 
                
                // Kolom Kiri: X=27 (Margin), Width=360
                $this->renderInvisibleBorderTable($slideLink, $plat, $colLeft, 27, 160, $startNo);
                
                // Kolom Kanan: X=407, Width=360 (Gap 20px)
                $startNoRight = $startNo + 25;
                $this->renderInvisibleBorderTable($slideLink, $plat, $colRight, 407, 160, $startNoRight);
                
                $pageCount++;
            }
        }

        ob_clean();
        flush();
        
        $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        $oWriterPPTX->save("php://output");
        exit;
    }

    // --- HELPER 1: INVISIBLE BORDER TABLE (SOLUSI LINK TIDAK NUMPUK) ---
    private function renderInvisibleBorderTable($slide, $title, $links, $x, $yStart, $startNo) {
        $totalW = 360; 
        $numW = 35;
        $urlW = 325;
        
        // 1. Header (Biru)
        $subH = $slide->createRichTextShape()->setHeight(35)->setWidth($totalW)->setOffsetX($x)->setOffsetY($yStart);
        $subH->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F3A60');
        $subH->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $subH->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $subH->createTextRun(strtoupper($title))->getFont()->setBold(true)->setSize(14)->setColor(new Color('FFFFFFFF'))->setName('Arial');
        
        if (empty($links)) return;

        // 2. Table Shape (Auto Height Row)
        $table = $slide->createTableShape(2); // 2 Kolom: [No] [Link]
        $table->setOffsetX($x);
        $table->setOffsetY($yStart + 40);
        $table->setWidth($totalW);
        
        foreach($links as $url) {
            $cleanUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            $row = $table->createRow();
            $row->setHeight(20); // Min height, akan melar otomatis jika teks panjang
            
            // Cell 1: Nomor
            $cellNum = $row->nextCell();
            $cellNum->setWidth($numW);
            $cellNum->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $cellNum->getActiveParagraph()->getAlignment()->setMarginRight(5); 
            $textNum = $cellNum->createTextRun($startNo . ".");
            $textNum->getFont()->setSize(10)->setName('Arial')->setColor(new Color('FF000000'));
            // Hapus Border
            $cellNum->getBorders()->getTop()->setLineStyle(Border::LINE_NONE);
            $cellNum->getBorders()->getBottom()->setLineStyle(Border::LINE_NONE);
            $cellNum->getBorders()->getLeft()->setLineStyle(Border::LINE_NONE);
            $cellNum->getBorders()->getRight()->setLineStyle(Border::LINE_NONE);

            // Cell 2: URL
            $cellUrl = $row->nextCell();
            $cellUrl->setWidth($urlW);
            $cellUrl->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $textUrl = $cellUrl->createTextRun($cleanUrl);
            $textUrl->getFont()->setSize(10)->setName('Arial')->setColor(new Color('FF000000'));
            // Hapus Border
            $cellUrl->getBorders()->getTop()->setLineStyle(Border::LINE_NONE);
            $cellUrl->getBorders()->getBottom()->setLineStyle(Border::LINE_NONE);
            $cellUrl->getBorders()->getLeft()->setLineStyle(Border::LINE_NONE);
            $cellUrl->getBorders()->getRight()->setLineStyle(Border::LINE_NONE);

            $startNo++;
        }
    }

    // --- HELPER 2: SECTION HEADER (TINGGI 100px) ---
    private function createSectionHeader($slide, $title, $y) {
        // Height ubah jadi 100 sesuai request 2x lebar
        $shape = $slide->createRichTextShape()->setHeight(100)->setWidth(794)->setOffsetX(0)->setOffsetY($y);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F3A60'); 
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); 
        $shape->createTextRun($title)->getFont()->setBold(true)->setSize(20)->setColor(new Color('FFFFFFFF'))->setName('Arial');
    }

    // --- HELPER 3: SPECIAL TABLE (KOLOM LEBAR 100px) ---
    private function createSpecialTable($slide, $headerTitle, $yStart, $data, $totalVal) {
        // Lebar Kolom 100 (Total 700px). Page 794. StartX 47.
        $colW = 100; 
        $rowH = 40; 
        $startX = 47; 
        
        $shape = $slide->createRichTextShape()->setHeight($rowH)->setWidth($colW * 7)->setOffsetX($startX)->setOffsetY($yStart);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F3A60');
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $text = $shape->createTextRun($headerTitle);
        $text->getFont()->setColor(new Color('FFFFFFFF'))->setBold(true)->setSize(14)->setName('Arial');
        $this->addBorder($shape); 

        $y = $yStart + $rowH; $currentX = $startX;
        $headers = array_keys($data); 
        foreach($headers as $hKey) {
            $this->createCell($slide, $hKey, $currentX, $y, $colW, $rowH, 'FF1F3A60', 'FFFFFFFF', true);
            $currentX += $colW;
        }
        $this->createCell($slide, "TOTAL", $currentX, $y, $colW, $rowH, 'FF1F3A60', 'FFFFFFFF', true);

        $y += $rowH; $currentX = $startX;
        foreach($data as $val) {
            $displayVal = ($val == 0) ? '-' : number_format($val);
            $this->createCell($slide, $displayVal, $currentX, $y, $colW, $rowH, 'FFFFFFFF', 'FF000000', true);
            $currentX += $colW;
        }
        $this->createCell($slide, number_format($totalVal), $currentX, $y, $colW, $rowH, 'FFFFFFFF', 'FF000000', true);
    }

    private function createCell($slide, $text, $x, $y, $w, $h, $bgColor, $textColor, $isBold) {
        $shape = $slide->createRichTextShape()->setHeight($h)->setWidth($w)->setOffsetX($x)->setOffsetY($y);
        $shape->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $run = $shape->createTextRun($text);
        $run->getFont()->setColor(new Color($textColor))->setSize(12)->setName('Arial');
        if($isBold) $run->getFont()->setBold(true);
        $this->addBorder($shape);
    }

    private function addBorder($shape) {
        $shape->getBorder()->setLineStyle(Border::LINE_SINGLE);
        $shape->getBorder()->setColor(new Color('FF000000')); 
        $shape->getBorder()->setLineWidth(1);
    }

    private function addBackgroundToSlide($slide, $global, $w, $h) {
        if (!empty($global['bg_image']) && file_exists(FCPATH . $global['bg_image'])) {
             $bg = $slide->createDrawingShape(); $bg->setPath(FCPATH . $global['bg_image'])->setWidth($w)->setHeight($h)->setOffsetX(0)->setOffsetY(0);
        }
    }

    private function prepareReportData() {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $db = \Config\Database::connect();
        $stats = $db->table($this->tableDaily)->where('date', $date)->get()->getRowArray();
        $settingsQuery = $db->table($this->tableSettings)->get()->getResultArray();
        $global = []; foreach($settingsQuery as $s) $global[$s['meta_key']] = $s['meta_value'];
        $links = $db->table($this->tableLinks)->where('DATE(created_at)', $date)->get()->getResultArray();
        $postCounts = $db->table($this->tableLinks)->select('platform, COUNT(id) as total')->where('DATE(created_at)', $date)->groupBy('platform')->get()->getResultArray();
        $pMap = []; foreach($postCounts as $pc) $pMap[$pc['platform']] = $pc['total'];
        $totalPost = array_sum($pMap);

        $genSettingsRow = $db->table('general_settings')->get()->getRowArray();
        $siteName = 'POLRES';
        if ($genSettingsRow) { if (isset($genSettingsRow['application_name'])) $siteName = strtoupper($genSettingsRow['application_name']); elseif (isset($genSettingsRow['site_title'])) $siteName = strtoupper($genSettingsRow['site_title']); }
        if(isset($global['polres_name'])) $siteName = $global['polres_name'];
        $ts = strtotime($date); $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $date_indo = $days[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $months[(int)date('m', $ts)] . ' ' . date('Y', $ts);
        return ['stats' => $stats, 'global' => $global, 'links' => $links, 'date_indo' => $date_indo, 'pMap' => $pMap, 'totalPost' => $totalPost, 'siteName' => $siteName];
    }

    private function detectPlatform($url) { $domain = strtolower($url); if (strpos($domain,'facebook')!==false) return 'Facebook'; if (strpos($domain,'instagram')!==false) return 'Instagram'; if (strpos($domain,'twitter')!==false || strpos($domain,'x.com')!==false) return 'Twitter/X'; if (strpos($domain,'tiktok')!==false) return 'TikTok'; if (strpos($domain,'youtube')!==false) return 'YouTube'; if (strpos($domain,'policetube')!==false || strpos($domain,'tribratanews')!==false) return 'PoliceTube'; return 'Lainnya'; }
    private function ensureDatabaseExists() { $db = \Config\Database::connect(); $forge = \Config\Database::forge(); if (!$db->tableExists($this->tableLinks)) { $forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'platform'=>['type'=>'VARCHAR','constraint'=>'50'],'url'=>['type'=>'TEXT'],'created_at'=>['type'=>'DATETIME','null'=>true]]); $forge->addKey('id', true)->createTable($this->tableLinks, true); } if (!$db->tableExists($this->tableDaily)) { $forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'date'=>['type'=>'DATE'],'ig'=>['type'=>'INT','default'=>0],'tw'=>['type'=>'INT','default'=>0],'fb'=>['type'=>'INT','default'=>0],'tt'=>['type'=>'INT','default'=>0],'pt'=>['type'=>'INT','default'=>0],'yt'=>['type'=>'INT','default'=>0],'total'=>['type'=>'INT','default'=>0],'content_images'=>['type'=>'TEXT','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]); $forge->addKey('id', true)->createTable($this->tableDaily, true); } if (!$db->tableExists($this->tableSettings)) { $forge->addField(['id'=>['type'=>'INT','auto_increment'=>true],'meta_key'=>['type'=>'VARCHAR','constraint'=>'50'],'meta_value'=>['type'=>'TEXT']]); $forge->addKey('id', true)->createTable($this->tableSettings, true); } }
}