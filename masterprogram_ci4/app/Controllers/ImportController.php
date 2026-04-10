<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\UploadModel;
use Config\Globals;

class ImportController extends BaseAdminController
{
    protected $uploadModel;
    protected $postModel;
    protected $db;

    public function __construct()
    {
        helper(['text', 'url', 'form', 'filesystem']);
        $this->uploadModel = new UploadModel();
        $this->postModel = new PostModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $data['title'] = 'Import WordPress (Fix Thumbnail Link)';
        $data['categories'] = $this->db->table('categories')->where('lang_id', $this->activeLang->id)->orderBy('name', 'ASC')->get()->getResult();

        echo view('admin/includes/_header', $data); 
        ?>
        
        <style>
            .scan-result { max-height: 400px; overflow-y: auto; background: #f4f4f4; border: 1px solid #ddd; padding: 10px; }
        </style>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> 1. SCAN XML</h3>
                    </div>
                    <div class="box-body">
                        <form id="formScan" enctype="multipart/form-data">
                            <div class="input-group input-group-lg">
                                <input type="file" name="xml_scan" class="form-control" required accept=".xml">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i> SCAN GAMBAR</button>
                                </span>
                            </div>
                        </form>
                        <br>
                        <div id="scanOutput" style="display:none;">
                            <table class="table table-bordered table-striped" style="font-size:12px;">
                                <thead>
                                    <tr><th>Judul Artikel</th><th>Nama File</th><th>Status</th></tr>
                                </thead>
                                <tbody id="scanTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border"><h3 class="box-title">2. Import Data (Database Fix)</h3></div>
                    <div class="box-body">
                        <div class="alert alert-success">
                            <i class="fa fa-check"></i> <b>Perbaikan Struktur Aktif:</b><br>
                            Script sudah di-bypass untuk menyimpan file fisik, mencegah MySQL Timeout, dan <b>Otomatis Merapikan Paragraf (SUPER AUTO-P)!</b>
                        </div>

                        <form id="formImport" enctype="multipart/form-data">
                            <div class="form-group"><label>Upload XML</label><input type="file" name="xml_file" class="form-control" required accept=".xml"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group"><label>Kategori</label><select name="category_id" id="category_id" class="form-control" required><?php if(!empty($data['categories'])): foreach($data['categories'] as $cat): ?><option value="<?= $cat->id; ?>"><?= esc($cat->name); ?></option><?php endforeach; endif; ?></select></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label>Mode Gambar</label><select name="download_images" id="download_images" class="form-control"><option value="1">YA - Auto (Local + Download)</option><option value="0">TIDAK - Teks Saja</option></select></div>
                                </div>
                            </div>
                            <div class="form-group"><label>Tanggal</label><select name="date_mode" id="date_mode" class="form-control"><option value="original">Tanggal Asli</option><option value="new">Tanggal Sekarang</option></select></div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="btnStart"><i class="fa fa-upload"></i> MULAI UPDATE & IMPORT</button>
                        </form>
                        <div id="progressArea" style="display:none; margin-top:20px;">
                            <div class="progress active"><div class="progress-bar progress-bar-success progress-bar-striped" id="progressBar" style="width: 0%">0%</div></div>
                            <div id="logConsole" style="height: 300px; overflow-y: scroll; font-family: monospace; font-size: 11px; background: #111; color: #0f0; padding:10px; border:1px solid #333;">
                                > Menunggu perintah...<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">Reset</h3></div><div class="box-body">
                    <form action="<?= adminUrl('import/undo'); ?>" method="post" onsubmit="return confirm('Hapus yg gagal?');"><input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"><input type="hidden" name="delete_mode" value="no_image"><button type="submit" class="btn btn-warning btn-sm btn-block">Hapus Data Gagal (No Image)</button></form>
                </div></div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                var csrfName = '<?= csrf_token() ?>'; var csrfHash = '<?= csrf_hash() ?>'; 

                // --- SCANNER ---
                $('#formScan').on('submit', function(e) {
                    e.preventDefault();
                    var btn = $(this).find('button'); btn.prop('disabled', true).text('Scanning...');
                    $('#scanOutput').hide(); $('#scanTableBody').empty();
                    var formData = new FormData(this); formData.append(csrfName, csrfHash);

                    $.ajax({
                        url: '<?= adminUrl('import/analyze-xml'); ?>',
                        type: 'POST', data: formData, contentType: false, cache: false, processData: false, dataType: 'json',
                        success: function(res) {
                            if(res.token) csrfHash = res.token;
                            btn.prop('disabled', false).html('<i class="fa fa-search"></i> SCAN GAMBAR');
                            if(res.status == 'success') {
                                $('#scanOutput').show();
                                var html = '';
                                $.each(res.data, function(i, item){
                                    var f = item.file_exists ? '<span class="badge badge-success">LOKAL</span>' : '<span class="badge badge-warning">WEB</span>';
                                    if(!item.filename) f = '<span class="badge badge-danger">KOSONG</span>';
                                    html += '<tr><td>'+item.title.substring(0,30)+'</td><td>'+item.filename+'</td><td>'+f+'</td></tr>';
                                });
                                $('#scanTableBody').html(html);
                            } else { alert("Error: " + res.message); }
                        },
                        error: function(xhr) { btn.prop('disabled', false).text('Error'); alert("Scan Error"); }
                    });
                });

                // --- IMPORT ---
                var totalItems = 0; 
                // [FIX 1] BATCH SIZE JADI 1 AGAR MYSQL TIDAK TIMEOUT
                var batchSize = 1; 

                $('#formImport').on('submit', function(e) {
                    e.preventDefault();
                    $('#formImport').slideUp(); $('#progressArea').show(); log("Upload XML dimulai...");
                    var formData = new FormData(this); formData.append(csrfName, csrfHash); 
                    $.ajax({
                        url: '<?= adminUrl('import/upload-xml'); ?>',
                        type: 'POST', data: formData, contentType: false, cache: false, processData: false, dataType: 'json',
                        success: function(resp) {
                            if(resp.token) csrfHash = resp.token;
                            if (resp.status == 'success') {
                                totalItems = resp.total;
                                log("✅ XML Valid! Total: " + totalItems);
                                runBatch(0);
                            } else { log("❌ Gagal Baca XML: " + resp.message); $('#formImport').slideDown(); }
                        },
                        error: function(xhr) { log("❌ ERROR Upload: " + xhr.responseText); $('#formImport').slideDown(); }
                    });
                });

                function runBatch(offset) {
                    var percent = (totalItems > 0) ? Math.round((offset / totalItems) * 100) : 0;
                    $('#progressBar').css('width', percent + '%').text(percent + '%');
                    var postData = {
                        offset: offset, limit: batchSize,
                        category_id: $('#category_id').val(), download_images: $('#download_images').val(), date_mode: $('#date_mode').val()
                    };
                    postData[csrfName] = csrfHash; 
                    $.ajax({
                        url: '<?= adminUrl('import/run-batch'); ?>',
                        type: 'POST', data: postData, dataType: 'text',
                        success: function(rawResponse) {
                            try {
                                var res = JSON.parse(rawResponse);
                                if(res.token) csrfHash = res.token;
                                if (res.status == 'done') {
                                    $('#progressBar').css('width', '100%').text('100%');
                                    log("🏁 SUKSES SELESAI!"); alert("Import Berhasil!");
                                    window.location.href = "<?= adminUrl('posts'); ?>";
                                } else if (res.status == 'continue') {
                                    if(res.msg) log(res.msg);
                                    runBatch(res.next_offset);
                                } else { log("❌ Error: " + res.message); }
                            } catch(e) {
                                var match = rawResponse.match(/\{"status":\s*"continue".*?\}/);
                                if(match) {
                                    var cleanRes = JSON.parse(match[0]);
                                    if(cleanRes.token) csrfHash = cleanRes.token;
                                    runBatch(cleanRes.next_offset);
                                } else {
                                    log("🔥 FATAL: " + rawResponse.substring(0, 200));
                                    setTimeout(function(){ runBatch(offset); }, 3000);
                                }
                            }
                        },
                        error: function(xhr) {
                            log("🔥 NETWORK ERROR: " + xhr.statusText);
                            setTimeout(function(){ runBatch(offset); }, 5000);
                        }
                    });
                }
                function log(msg) {
                    var t = new Date().toLocaleTimeString();
                    $('#logConsole').append('<div>['+t+'] '+msg+'</div>');
                    var d = $('#logConsole'); d.scrollTop(d.prop("scrollHeight"));
                }
            });
        </script>
        <?php echo view('admin/includes/_footer');
    }

    // --- ANALYZER ---
    public function analyzeXml() {
        session_write_close(); ob_clean();
        try {
            $file = $this->request->getFile('xml_scan');
            if (!$file->isValid()) throw new \Exception("File Error");
            $content = file_get_contents($file->getTempName());
            $content = str_replace(['wp:', 'content:', 'excerpt:', 'dc:'], ['wp_', 'content_', 'excerpt_', 'dc_'], $content);
            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            
            $map = [];
            foreach($xml->channel->item as $item) {
                if((string)$item->wp_post_type == 'attachment') $map[(string)$item->wp_post_id] = (string)$item->wp_attachment_url;
            }

            $results = []; $count = 0; $limit = 50;
            foreach($xml->channel->item as $item) {
                if((string)$item->wp_post_type != 'post') continue;
                if($count >= $limit) break;

                $thumbId = 0;
                if(isset($item->wp_postmeta)) {
                    foreach($item->wp_postmeta as $m) {
                        if((string)$m->wp_meta_key == '_thumbnail_id') { $thumbId = (string)$m->wp_meta_value; break; }
                    }
                }
                $imgUrl = ($thumbId && isset($map[$thumbId])) ? $map[$thumbId] : null;
                $filename = $imgUrl ? urldecode(basename(parse_url($imgUrl, PHP_URL_PATH))) : '';
                $fileExists = $filename && file_exists(FCPATH . 'uploads/manual_import/' . $filename);

                $results[] = ['title' => (string)$item->title, 'filename' => $filename, 'file_exists' => $fileExists];
                $count++;
            }
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success', 'data'=>$results, 'token'=>csrf_hash()]); exit;
        } catch (\Throwable $e) {
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error', 'message'=>$e->getMessage(), 'token'=>csrf_hash()]); exit;
        }
    }

    // --- UPLOAD ---
    public function uploadXml() {
        session_write_close(); while (ob_get_level()) { ob_end_clean(); }
        try {
            $file = $this->request->getFile('xml_file');
            $path = WRITEPATH . 'uploads/';
            if(!is_dir($path)) mkdir($path, 0755, true);
            $tempName = 'import_' . user()->id . '.xml';
            $file->move($path, $tempName, true);
            
            $content = file_get_contents($path . $tempName);
            $content = str_replace(['wp:', 'content:', 'excerpt:', 'dc:'], ['wp_', 'content_', 'excerpt_', 'dc_'], $content);
            file_put_contents($path . $tempName, $content); 

            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $count = 0;
            foreach($xml->channel->item as $item) { if((string)$item->wp_post_type == 'post') $count++; }
            
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success', 'total'=>$count, 'token'=>csrf_hash()]); exit; 
        } catch (\Throwable $e) { 
            header('Content-Type: application/json');
            echo json_encode(['status'=>'error', 'message'=>$e->getMessage(), 'token'=>csrf_hash()]); exit; 
        }
    }

    // --- BATCH PROCESS ---
    public function runBatch() {
        session_write_close(); 
        @ini_set('memory_limit', '1024M'); 
        @set_time_limit(0);
        $this->db->reconnect(); 
        while (ob_get_level()) { ob_end_clean(); }

        try {
            $offset = (int)inputPost('offset'); $limit = (int)inputPost('limit');
            $catId = inputPost('category_id'); $dlImg = inputPost('download_images'); $dMode = inputPost('date_mode');
            $path = WRITEPATH . 'uploads/import_' . user()->id . '.xml';
            if (!file_exists($path)) { header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message'=>'Sesi habis.', 'token'=>csrf_hash()]); exit; }

            $content = file_get_contents($path);
            $content = str_replace(['wp:', 'content:', 'excerpt:', 'dc:'], ['wp_', 'content_', 'excerpt_', 'dc_'], $content);
            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

            $map = [];
            if($dlImg == 1) {
                foreach($xml->channel->item as $item) {
                    if((string)$item->wp_post_type == 'attachment') $map[(string)$item->wp_post_id] = (string)$item->wp_attachment_url;
                }
            }

            $processed = 0; $inserted = 0; $skipped = 0; $updated = 0; $idx = 0; $logMsg = "";
            $hasLangInImages = $this->db->fieldExists('lang_id', 'images');

            foreach($xml->channel->item as $item) {
                if((string)$item->wp_post_type != 'post') continue;
                if($idx < $offset) { $idx++; continue; }
                if($processed >= $limit) break;

                $idx++; $processed++;
                $title = (string)$item->title;
                $slug = !empty($item->wp_post_name) ? (string)$item->wp_post_name : strSlug($title);
                $contentPost = (string)$item->content_encoded;

                // --- FIX 4: FORMAT PARAGRAF WORDPRESS (SUPER AUTO-P) ---
                // Cek apakah konten XML tidak memiliki tag <p> (kurang dari 2)
                if (substr_count($contentPost, '<p>') < 2) {
                    // 1. Standarkan enter (buang karakter aneh)
                    $contentPost = str_replace(["\r\n", "\r"], "\n", $contentPost);
                    
                    // 2. Pecah string setiap kali ketemu 2 enter atau lebih (termasuk spasi di antaranya)
                    $paragraphs = preg_split('/\n\s*\n/', $contentPost, -1, PREG_SPLIT_NO_EMPTY);
                    
                    $newContent = '';
                    foreach ($paragraphs as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            // 3. Ubah 1 kali enter di dalam paragraf menjadi <br>
                            $line = nl2br($line);
                            // 4. Bungkus string dengan tag <p>...</p>
                            $newContent .= "<p>{$line}</p>\n";
                        }
                    }
                    
                    // 5. Ganti variabel lama jika hasil pecah paragraf sukses
                    if(!empty($newContent)) {
                        $contentPost = $newContent;
                    }
                }
                // ---------------------------------------------------------

                $existingPost = $this->postModel->getPostBySlug($slug);
                $imgId = 0; $imgUrl = ''; 

                if($dlImg == 1) {
                    $thumbId = 0;
                    if(isset($item->wp_postmeta)) {
                        foreach($item->wp_postmeta as $m) {
                            if((string)$m->wp_meta_key == '_thumbnail_id') { $thumbId = (string)$m->wp_meta_value; break; }
                        }
                    }
                    $targetUrl = ($thumbId && isset($map[$thumbId])) ? $map[$thumbId] : null;
                    if(!$targetUrl && preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $contentPost, $m)) $targetUrl = $m[1];

                    if($targetUrl) {
                        $resImg = $this->processImage($targetUrl, $slug, $hasLangInImages);
                        if($resImg['status']) {
                            $imgId = $resImg['id']; 
                            $imgUrl = $resImg['url'];
                            $logMsg .= "<span style='color:#0f0'>✓ Img OK: ".basename($targetUrl)."</span><br>";
                        }
                    }
                }

                $imageData = [
                    'image_id' => $imgId,
                    'image_url' => $imgUrl,
                ];

                // [FIX 2] Mencegah Error "MySQL server has gone away"
                $this->db->reconnect(); 

                if ($existingPost) {
                    if($imgId > 0) {
                        $this->db->table('posts')->where('id', $existingPost->id)->update($imageData);
                        // Update teks paragraf ke artikel yg sudah ada
                        $this->db->table('posts')->where('id', $existingPost->id)->update(['content' => $contentPost]); 
                        
                        $logMsg .= "<span style='color:#3c8dbc'>♻️ Update Post: $slug</span><br>";
                        $updated++;
                    } else {
                        // Update teks paragraf MESKIPUN tidak download gambar baru
                        $this->db->table('posts')->where('id', $existingPost->id)->update(['content' => $contentPost]); 
                        $logMsg .= "<span style='color:#aaa'>- Skip Img, Update Text: $slug</span><br>";
                        $skipped++;
                    }
                } else {
                    $postData = [
                        'lang_id'     => $this->activeLang->id,
                        'title'       => $title,
                        'slug'        => $slug,
                        'summary'     => '',
                        'content'     => $contentPost,
                        'category_id' => $catId,
                        'image_id'    => $imgId,
                        'image_url'   => $imgUrl,
                        'pageviews'   => 0,
                        'post_type'   => 'article',
                        'user_id'     => user()->id,
                        'status'      => 1,
                        'visibility'  => 1,
                        'created_at'  => ($dMode == 'new') ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime((string)$item->pubDate)),
                        'updated_at'  => date('Y-m-d H:i:s'),
                        'site_id'     => 1,
                    ];

                    $this->postModel->addPostImport($postData);
                    $inserted++;
                }
            }

            if($processed < $limit) { @unlink($path); header('Content-Type: application/json'); echo json_encode(['status'=>'done', 'token'=>csrf_hash()]); exit; }
            header('Content-Type: application/json'); echo json_encode(['status'=>'continue', 'next_offset'=>$offset+$processed, 'inserted'=>$inserted, 'skipped'=>$updated, 'msg'=>$logMsg, 'token'=>csrf_hash()]); exit;

        } catch (\Throwable $e) { 
            header('Content-Type: application/json'); echo json_encode(['status'=>'error', 'message' => $e->getMessage(), 'token'=>csrf_hash()]); exit; 
        }
    }

    // [FIX 3] FUNGSI DOWNLOADER BYPASS (Langsung simpan fisik tanpa resize yang bikin 404)
    private function processImage($url, $slug, $hasLang) {
        $filename = urldecode(basename(parse_url($url, PHP_URL_PATH)));
        $filename = strtok($filename, '?'); 
        
        $localPath = FCPATH . 'uploads/manual_import/' . $filename;
        $content = false;

        if (file_exists($localPath)) {
            $content = file_get_contents($localPath);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode != 200) return ['status'=>false, 'msg'=>'Gagal Download'];
        }

        if (!$content) return ['status'=>false, 'msg'=>'Konten Kosong'];

        // Simpan langsung ke folder images
        $month = date('Ym');
        $dirPath = FCPATH . 'uploads/images/' . $month . '/';
        if(!is_dir($dirPath)) mkdir($dirPath, 0755, true);

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(empty($ext) || strlen($ext) > 4) $ext = 'jpg';
        
        $uniq = uniqid();
        $finalName = 'site_1_image_' . $uniq . '.' . $ext;
        $finalPath = $dirPath . $finalName;
        
        file_put_contents($finalPath, $content);
        if(filesize($finalPath) < 100) {
            @unlink($finalPath);
            return ['status'=>false, 'msg'=>'File Rusak'];
        }

        $dbPath = 'uploads/images/' . $month . '/' . $finalName;

        // Masukkan ke TABEL IMAGES dengan storage 'local'
        $imgData = [
            'image_big'     => $dbPath,
            'image_default' => $dbPath,
            'image_slider'  => $dbPath,
            'image_mid'     => $dbPath,
            'image_small'   => $dbPath,
            'image_mime'    => $ext,
            'storage'       => 'local', 
            'file_name'     => $slug,
            'user_id'       => user()->id
        ];
        if($hasLang) $imgData['lang_id'] = $this->activeLang->id;
        
        $imgId = $this->postModel->addImageImport($imgData);
        return ['status'=>true, 'id'=>$imgId, 'url'=> $dbPath];
    }

    public function testSingleImage() { /* ... */ }
    
    public function undoImport() { 
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));
        $mode = inputPost('delete_mode');
        $b = $this->db->table('posts');
        if($mode == 'no_image') $b->where('user_id', user()->id)->where('image_id', 0)->delete();
        elseif($mode == 'today') $b->where('user_id', user()->id)->where('created_at >=', date('Y-m-d 00:00:00'))->delete();
        return redirect()->back()->with('success', 'Data dibersihkan.');
    }
    
    public function debug() { phpinfo(); }
}