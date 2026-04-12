<div class="row">
    <div class="col-sm-12">
        <h1 class="page-title">Laporan Media Sosial</h1>
        <ol class="breadcrumb">
            <li><a href="<?= adminUrl(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Laporan Medsos</li>
        </ol>
    </div>
</div>

<style>
    /* TAB MENU */
    .nav-tabs-custom > .nav-tabs { background-color: #1F3A60; border-bottom-color: #f39c12; }
    .nav-tabs-custom > .nav-tabs > li.active > a, 
    .nav-tabs-custom > .nav-tabs > li.active > a:hover { 
        border-top-color: #f39c12; border-left-color: #f39c12; border-right-color: #f39c12; 
        color: #1F3A60; font-weight: bold; 
    }
    .nav-tabs-custom > .nav-tabs > li > a { color: #fff; font-weight: 600; }
    .nav-tabs-custom > .nav-tabs > li > a:hover { color: #f39c12; }

    /* card STYLES */
    .modern-card {
        background: #fff; border-radius: 8px; card-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 1px solid #e1e1e1; padding: 20px; margin-bottom: 20px; border-top: 4px solid #1F3A60;
    }
    .modern-card-header { font-size: 18px; font-weight: bold; color: #1F3A60; margin-bottom: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }

    /* ENGAGEMENT INPUT CARDS */
    .eng-card {
        background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; padding: 10px; text-align: center; margin-bottom: 15px;
        transition: transform 0.2s;
    }
    .eng-card:hover { transform: translateY(-3px); card-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #1F3A60; }
    .eng-icon { font-size: 24px; margin-bottom: 5px; display: block; }
    .eng-label { font-weight: bold; font-size: 12px; color: #555; text-transform: uppercase; display: block; margin-bottom: 5px; }
    .eng-input-field { text-align: center; font-weight: bold; font-size: 16px; border: 1px solid #ccc; height: 40px; }

    /* COLORS */
    .c-ig { color: #E1306C; } .c-tw { color: #1DA1F2; } .c-fb { color: #1877F2; }
    .c-tt { color: #000000; } .c-pt { color: #FFD700; text-shadow: 1px 1px 1px #333; } .c-yt { color: #FF0000; }

    /* PREVIEW TABLE (BLUE HEADER) */
    .tbl-blue { width: 100%; border-collapse: collapse; text-align: center; }
    .tbl-blue th { background-color: #1F3A60; color: white; padding: 12px; border: 1px solid #000; text-transform: uppercase; font-size: 12px; }
    .tbl-blue td { background-color: #fff; color: #000; padding: 10px; border: 1px solid #000; font-weight: bold; }
    .tbl-blue-header { background-color: #1F3A60; color: white; padding: 10px; font-weight: bold; text-align: center; border: 1px solid #000; border-bottom: none; letter-spacing: 1px; }

    /* MINI TABLE (TAB 1) */
    .mini-table-container { 
        min-height: 450px; /* Tinggi pas untuk 7 baris */
        max-height: 450px; 
        overflow-y: auto; 
        background: #fff; 
    }
    .mini-table th { 
        background-color: #1F3A60; color: #fff; /* Header Biru Gelap */
        font-weight: bold; padding: 10px; border-bottom: 2px solid #000; 
        text-align: center; text-transform: uppercase; font-size: 11px;
    }
    .mini-table td { padding: 8px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
    .col-no { width: 40px; text-align: center; font-weight: bold; color: #555; background: #fafafa; }
    
    .mini-pagination { text-align: right; padding: 8px 10px; background: #f9f9f9; border-top: 1px solid #ddd; border-radius: 0 0 4px 4px; }
    .mini-btn { background: #fff; border: 1px solid #ccc; padding: 4px 10px; cursor: pointer; font-size: 12px; margin-left: 3px; border-radius: 3px; font-weight: bold; }
    .mini-btn:hover { background: #1F3A60; color: #fff; border-color: #1F3A60; }
    .mini-info { font-size: 12px; color: #555; margin-right: 10px; font-weight: 600; }

    /* TABLE ELEGANT (TAB 2) - MEPET */
    .table-elegant { width: 100%; border-collapse: separate; border-spacing: 0 4px; }
    .table-elegant thead th { background-color: #1F3A60; color: #fff; padding: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 12px; font-weight: 600; border: none; }
    .table-elegant thead th:first-child { border-radius: 6px 0 0 6px; text-align: center; }
    .table-elegant thead th:last-child { border-radius: 0 6px 6px 0; }
    .table-elegant tbody tr { background-color: #fff; card-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: transform 0.2s, card-shadow 0.2s; }
    .table-elegant tbody tr:hover { transform: translateY(-2px); card-shadow: 0 5px 10px rgba(0,0,0,0.1); z-index: 10; position: relative; }
    .table-elegant td { padding: 8px 12px; border: none; vertical-align: middle; border-top: 1px solid #f9f9f9; border-bottom: 1px solid #f9f9f9; color: #555; font-size: 13px; }
    .table-elegant td:first-child { border-left: 1px solid #f9f9f9; border-radius: 6px 0 0 6px; text-align: center; font-weight: bold; color: #1F3A60; }
    .table-elegant td:last-child { border-right: 1px solid #f9f9f9; border-radius: 0 6px 6px 0; }

    .badge-plat { padding: 4px 10px; border-radius: 15px; color: #fff; font-size: 10px; font-weight: bold; display: inline-block; min-width: 90px; text-align: center; card-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .bg-ig { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
    .bg-fb { background-color: #1877F2; } .bg-tw { background-color: #1DA1F2; } .bg-tt { background-color: #000; }
    .bg-yt { background-color: #FF0000; } .bg-pt { background-color: #FFD700; color: #000; text-shadow: none; } .bg-def { background-color: #777; }

    /* PAGINATION TAB 2 */
    .pagination-wrap { margin-top: 15px; text-align: right; }
    .pagination-wrap .pagination { margin: 0; }
    .pagination-wrap .pagination li a { border: none; color: #555; margin: 0 2px; border-radius: 4px; font-weight: bold; background: #eee; font-size: 12px; padding: 5px 10px; }
    .pagination-wrap .pagination li.active a { background-color: #1F3A60; color: #fff; card-shadow: 0 2px 5px rgba(31,58,96,0.3); }

    /* IMAGES */
    .thumb-wrap { position: relative; display: inline-block; margin: 5px; border: 2px solid #fff; card-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    .btn-del-img { position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border: 2px solid #fff; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: bold; card-shadow: 0 2px 4px rgba(0,0,0,0.2); }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= (!isset($_GET['tab']) || $_GET['tab'] == 'all') ? 'active' : ''; ?>">
                    <a href="#tab_all" data-toggle="tab"><i class="fa fa-link"></i> INPUT LINK</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'daily') ? 'active' : ''; ?>">
                    <a href="#tab_daily" data-toggle="tab"><i class="fa fa-pencil-square-o"></i> LAPORAN HARIAN</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>">
                    <a href="#tab_monthly" data-toggle="tab"><i class="fa fa-calendar"></i> LAPORAN BULANAN</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'active' : ''; ?>">
                    <a href="#tab_settings" data-toggle="tab"><i class="fa fa-cogs"></i> PENGATURAN</a>
                </li>
            </ul>
            
            <div class="tab-content">
                
                <div class="tab-pane <?= (!isset($_GET['tab']) || $_GET['tab'] == 'all') ? 'active' : ''; ?>" id="tab_all">
                    
                    <div class="modern-card">
                        <div class="modern-card-header"><i class="fa fa-plus-circle"></i> TAMBAH LINK VIRALISASI</div>
                        <form action="<?= adminUrl('smart-report/medsos/save'); ?>" method="post">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <textarea name="raw_links" class="form-control" rows="5" placeholder="Paste laporan di sini (campur teks & link)..." style="resize: vertical; font-size: 14px; border: 1px solid #ccc; background: #fdfdfd;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary float-end" style="background-color: #1F3A60; border-color: #1F3A60;"><i class="fa fa-save"></i> EKSTRAK & SIMPAN</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>

                    <div class="row">
                        <?php 
                        // DEFINISI FIX 6 PLATFORM (AGAR SELALU MUNCUL)
                        $fixedPlatforms = [
                            'Instagram' => ['icon'=>'fa-instagram', 'color'=>'#E1306C'],
                            'Twitter/X' => ['icon'=>'fa-twitter', 'color'=>'#1DA1F2'],
                            'Facebook'  => ['icon'=>'fa-facebook-square', 'color'=>'#1877F2'],
                            'TikTok'    => ['icon'=>'fa-music', 'color'=>'#000000'],
                            'PoliceTube'=> ['icon'=>'fa-play-circle', 'color'=>'#FFD700'],
                            'YouTube'   => ['icon'=>'fa-youtube-play', 'color'=>'#FF0000'],
                        ];

                        $counter = 0;
                        foreach ($fixedPlatforms as $keyName => $style): 
                            $links = [];
                            if(isset($groupedLinks[$keyName])) { $links = $groupedLinks[$keyName]; }
                            // Mapping Lainnya -> PoliceTube
                            if($keyName == 'PoliceTube' && isset($groupedLinks['Lainnya'])) { $links = array_merge($links, $groupedLinks['Lainnya']); }

                            $tableId = 'tbl_mini_' . $counter++;
                        ?>
                            <div class="col-md-4">
                                <div class="card card-solid" style="border: 1px solid #ddd; border-top: 3px solid <?= $style['color']; ?>; card-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    <div class="card-header with-border" style="background-color: #f9f9f9; padding: 10px;">
                                        <h4 class="card-title" style="color: <?= $style['color']; ?>; font-weight: bold; font-size: 16px;">
                                            <i class="fa <?= $style['icon']; ?>"></i> <?= esc($keyName); ?>
                                        </h4>
                                        <span class="label float-end" style="background-color: <?= $style['color']; ?>; color: <?= ($style['color']=='#FFD700') ? '#000' : '#fff'; ?>;"><?= count($links); ?></span>
                                    </div>
                                    <div class="card-body no-padding mini-table-container">
                                        <table class="table table-striped table-hover table-sm mini-table" id="<?= $tableId; ?>" style="margin-bottom: 0;">
                                            <thead>
                                                <tr><th class="col-no">No</th><th>Link Postingan</th></tr>
                                            </thead>
                                            <tbody class="list-container">
                                                <?php if(!empty($links)): ?>
                                                    <?php foreach ($links as $index => $row): ?>
                                                    <tr class="link-item" style="<?= ($index >= 7) ? 'display:none;' : ''; ?>">
                                                        <td class="col-no"><?= $index + 1; ?></td>
                                                        <td style="padding: 8px;">
                                                            <a href="<?= esc($row['url']); ?>" target="_blank" style="color: #333; text-decoration: none; word-break: break-all; display: block; line-height: 1.3;">
                                                                <?= esc($row['url']); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php for($i = count($links); $i < 7; $i++): ?>
                                                        <tr class="link-item spacer-row" style="display:none;"><td>&nbsp;</td><td>&nbsp;</td></tr>
                                                    <?php endfor; ?>
                                                <?php else: ?>
                                                    <tr><td colspan="2" class="text-center text-muted" style="padding:20px;">Belum ada data.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mini-pagination">
                                        <?php if(count($links) > 7): ?>
                                            <span class="mini-info">Hal <span class="curr-page">1</span>/<?= ceil(count($links)/7); ?></span>
                                            <button type="button" class="mini-btn" onclick="paginateMini('<?= $tableId; ?>', -1)"> < </button>
                                            <button type="button" class="mini-btn" onclick="paginateMini('<?= $tableId; ?>', 1)"> > </button>
                                        <?php else: ?>
                                            <span class="mini-info">Total: <?= count($links); ?> Data</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (($counter % 3) == 0): ?><div class="clearfix hidden-xs hidden-sm"></div><?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'daily') ? 'active' : ''; ?>" id="tab_daily">
                    
                    <div class="modern-card" style="padding: 15px; margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="<?= current_url(); ?>" method="get" class="form-inline">
                                    <input type="hidden" name="tab" value="daily">
                                    <label class="m-r-5">PILIH TANGGAL:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="<?= esc($selectedDate); ?>" onchange="this.form.submit()" style="font-weight: bold;">
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?= adminUrl('smart-report/medsos/view_report?date='.$selectedDate); ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> PREVIEW</a>
                                <a href="<?= adminUrl('smart-report/medsos/export_pptx?date='.$selectedDate); ?>" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-file-powerpoint-o"></i> DOWNLOAD POWER POINT</a>
                                <a href="<?= adminUrl('smart-report/medsos/export_pdf?date='.$selectedDate); ?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> DOWNLOAD PDF</a>
                               
                            </div>
                        </div>
                    </div>

                    <div class="card card-success">
                        <div class="card-header with-border"><h3 class="card-title"><i class="fa fa-bar-chart"></i> 1. DATA ENGAGEMENT & STATISTIK</h3></div>
                        <form action="<?= adminUrl('smart-report/medsos/save-engagement'); ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="date" value="<?= esc($selectedDate); ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-instagram eng-icon c-ig"></i><span class="eng-label">INSTAGRAM</span><input type="number" name="ig" class="form-control eng-input-field eng-input" value="<?= $dailyStats['ig'] ?? 0; ?>"></div></div>
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-twitter eng-icon c-tw"></i><span class="eng-label">TWITTER / X</span><input type="number" name="tw" class="form-control eng-input-field eng-input" value="<?= $dailyStats['tw'] ?? 0; ?>"></div></div>
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-facebook-official eng-icon c-fb"></i><span class="eng-label">FACEBOOK</span><input type="number" name="fb" class="form-control eng-input-field eng-input" value="<?= $dailyStats['fb'] ?? 0; ?>"></div></div>
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-music eng-icon c-tt"></i><span class="eng-label">TIKTOK</span><input type="number" name="tt" class="form-control eng-input-field eng-input" value="<?= $dailyStats['tt'] ?? 0; ?>"></div></div>
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-play-circle eng-icon c-pt"></i><span class="eng-label">POLICETUBE</span><input type="number" name="pt" class="form-control eng-input-field eng-input" value="<?= $dailyStats['pt'] ?? 0; ?>"></div></div>
                                    <div class="col-md-2 col-sm-4 col-xs-6"><div class="eng-card"><i class="fa fa-youtube-play eng-icon c-yt"></i><span class="eng-label">YOUTUBE</span><input type="number" name="yt" class="form-control eng-input-field eng-input" value="<?= $dailyStats['yt'] ?? 0; ?>"></div></div>
                                </div>
                                <div class="text-center" style="margin-top: 10px;">
                                    <label style="font-size: 16px;">TOTAL ENGAGEMENT: <span id="total_engagement" class="badge" style="font-size: 18px; background-color: #1F3A60;"><?= $dailyStats['total'] ?? 0; ?></span></label>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-success btn-block" style="font-weight: bold;"><i class="fa fa-save"></i> SIMPAN DATA ENGAGEMENT</button>
                            </div>
                        </form>

                        <div class="card-body" style="background: #f0f0f0; padding: 20px; border-top: 2px solid #ccc;">
                            <h5 class="text-center text-bold" style="color: #555; margin-bottom: 20px;">PREVIEW TABEL LAPORAN (Hasil Simpan)</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="tbl-blue-header">JUMLAH POSTINGAN MEDIA SOSIAL</div>
                                    <table class="tbl-blue">
                                        <thead><tr><th>INSTAGRAM</th><th>TWITTER / X</th><th>FACEBOOK</th><th>TIKTOK</th><th>POLICETUBE</th><th>YOUTUBE</th><th>TOTAL</th></tr></thead>
                                        <tbody><tr><td><?= $postCounts['Instagram']??0; ?></td><td><?= $postCounts['Twitter/X']??0; ?></td><td><?= $postCounts['Facebook']??0; ?></td><td><?= $postCounts['TikTok']??0; ?></td><td><?= ($postCounts['PoliceTube'] ?? 0) + ($postCounts['Lainnya'] ?? 0); ?></td><td><?= $postCounts['YouTube']??0; ?></td><td><?= $totalPost??0; ?></td></tr></tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="tbl-blue-header">JUMLAH ENGAGEMENT MEDIA SOSIAL</div>
                                    <table class="tbl-blue">
                                        <thead><tr><th>INSTAGRAM</th><th>TWITTER / X</th><th>FACEBOOK</th><th>TIKTOK</th><th>POLICETUBE</th><th>YOUTUBE</th><th>TOTAL</th></tr></thead>
                                        <tbody><tr><td><?= number_format($dailyStats['ig']??0); ?></td><td><?= number_format($dailyStats['tw']??0); ?></td><td><?= number_format($dailyStats['fb']??0); ?></td><td><?= number_format($dailyStats['tt']??0); ?></td><td><?= number_format($dailyStats['pt']??0); ?></td><td><?= number_format($dailyStats['yt']??0); ?></td><td><?= number_format($dailyStats['total']??0); ?></td></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-warning">
                        <div class="card-header with-border"><h3 class="card-title"><i class="fa fa-image"></i> 2. LAMPIRAN KONTEN HARIAN</h3></div>
                        <div class="card-body">
                            <form action="<?= adminUrl('smart-report/medsos/save-content'); ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field(); ?><input type="hidden" name="date" value="<?= esc($selectedDate); ?>">
                                <div class="form-group"><label>Upload Gambar (Bisa Banyak):</label><div class="input-group"><input type="file" name="content_images[]" class="form-control" multiple accept="image/*"><span class="input-group-btn"><button type="submit" class="btn btn-warning"><i class="fa fa-upload"></i> UPLOAD</button></span></div></div>
                            </form>
                            <hr><label>Gambar Tersimpan:</label>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; background: #f9f9f9; padding: 15px; border: 1px solid #ddd; min-height: 100px; border-radius: 5px;">
                                <?php if(!empty($dailyStats['content_images'])) { $imgs = json_decode($dailyStats['content_images'], true); if(is_array($imgs) && count($imgs) > 0) { foreach($imgs as $img) { if(file_exists(FCPATH . $img)) { echo '<div class="thumb-wrap"><img src="'.base_url($img).'" style="width: 100px; height: 100px; object-fit: cover;"><form action="'.adminUrl('smart-report/medsos/delete-content-image').'" method="post" onsubmit="return confirm(\'Hapus gambar ini?\');">'.csrf_field().'<input type="hidden" name="date" value="'.esc($selectedDate).'"><input type="hidden" name="image_path" value="'.esc($img).'"><button type="submit" class="btn-del-img" title="Hapus"><i class="fa fa-times"></i></button></form></div>'; } } } else { echo '<small class="text-muted">Belum ada gambar.</small>'; } } else { echo '<small class="text-muted">Belum ada gambar.</small>'; } ?>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header with-border"><h3 class="card-title"><i class="fa fa-list"></i> DAFTAR LINK VIRALISASI</h3></div>
                        <div class="card-body" style="background-color: #fcfcfc;">
                            <table class="table-elegant">
                                <thead><tr><th style="width: 60px;">NO</th><th style="width: 150px;">PLATFORM</th><th>LINK TAUTAN</th></tr></thead>
                                <tbody>
                                    <?php 
                                    if(!empty($dailyLinks)): 
                                        $page = (int)($_GET['page_daily'] ?? 1);
                                        $perPage = 10;
                                        $no = ($page - 1) * $perPage + 1;
                                        foreach($dailyLinks as $det): 
                                            $plat = $det['platform']; $bgClass = 'bg-def';
                                            if(strpos($plat,'Instagram')!==false) $bgClass='bg-ig'; elseif(strpos($plat,'Twitter')!==false || strpos($plat,'X')!==false) $bgClass='bg-tw'; elseif(strpos($plat,'Facebook')!==false) $bgClass='bg-fb'; elseif(strpos($plat,'TikTok')!==false) $bgClass='bg-tt'; elseif(strpos($plat,'YouTube')!==false) $bgClass='bg-yt'; elseif(strpos($plat,'PoliceTube')!==false || strpos($plat,'Lainnya')!==false) $bgClass='bg-pt';
                                            $platDisplay = ($plat == 'Lainnya') ? 'PoliceTube' : $plat;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-center"><span class="badge-plat <?= $bgClass; ?>"><?= strtoupper(esc($platDisplay)); ?></span></td>
                                            <td><a href="<?= esc($det['url']); ?>" target="_blank" style="color: #2c3e50; text-decoration: none; font-weight: 500;"><i class="fa fa-external-link" style="color: #999; margin-right: 5px;"></i> <?= esc($det['url']); ?></a></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" class="text-center" style="padding: 20px;">Belum ada link viralisasi hari ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer pagination-wrap"><?= $pager; ?></div>
                    </div>
                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>" id="tab_monthly">
                    <form action="<?= current_url(); ?>" method="get" class="form-inline m-b-15"><input type="hidden" name="tab" value="monthly"><div class="form-group"><label class="m-r-5">Bulan:</label><select name="month" class="form-control input-sm"><?php $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; foreach($months as $k => $v): ?><option value="<?= $k; ?>" <?= ($k == $selectedMonth) ? 'selected' : ''; ?>><?= $v; ?></option><?php endforeach; ?></select></div><div class="form-group"><label class="m-r-5 m-l-5">Tahun:</label><select name="year" class="form-control input-sm"><?php for($y=date('Y'); $y>=2020; $y--): ?><option value="<?= $y; ?>" <?= ($y == $selectedYear) ? 'selected' : ''; ?>><?= $y; ?></option><?php endfor; ?></select></div><button type="submit" class="btn btn-primary btn-sm m-l-10"><i class="fa fa-eye"></i> Filter</button></form>
                    <div class="row"><div class="col-md-6"><table class="table table-bordered"><thead class="bg-gray"><tr><th>Platform</th><th>Total Link</th></tr></thead><tbody><?php if(!empty($monthlySummary)): foreach($monthlySummary as $sum): ?><tr><td><?= $sum['platform']; ?></td><td class="text-bold"><?= $sum['total']; ?></td></tr><?php endforeach; endif; ?></tbody></table></div></div>
                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'active' : ''; ?>" id="tab_settings">
                    <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Jika gambar preview tidak berubah setelah upload, silakan Refresh halaman (F5).</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header with-border"><h3 class="card-title">Upload Template Laporan</h3></div>
                                <form action="<?= adminUrl('smart-report/medsos/save-settings'); ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>NAMA POLRES (Untuk Cover)</label>
                                            <input type="text" name="polres_name" class="form-control" placeholder="Contoh: POLRESTA BANYUWANGI" value="<?= esc($globalSettings['polres_name'] ?? ''); ?>">
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label>Cover Laporan (Portrait)</label>
                                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                                        </div>
                                        <div class="form-group">
                                            <label>Background Laporan (Halaman Link)</label>
                                            <input type="file" name="bg_image" class="form-control" accept="image/*">
                                        </div>
                                        <div class="form-group">
                                            <label>Background Lampiran Konten</label>
                                            <input type="file" name="bg_lampiran" class="form-control" accept="image/*">
                                            <small class="text-muted">Khusus halaman lampiran gambar.</small>
                                        </div>
                                    </div>
                                    <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN PENGATURAN</button></div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-solid">
                                <div class="card-header with-border"><h3 class="card-title">Preview Template</h3></div>
                                <div class="card-body text-center">
                                    <strong>Nama Polres:</strong> <span class="label badge bg-success"><?= esc($globalSettings['polres_name'] ?? 'Belum disetting'); ?></span><br><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Cover:</strong><br><br>
                                            <?php if(!empty($globalSettings['cover_image']) && file_exists(FCPATH . $globalSettings['cover_image'])): ?><img src="<?= base_url($globalSettings['cover_image']); ?>?t=<?= time(); ?>" style="width: 100%; border: 1px solid #ddd; padding: 3px; card-shadow: 2px 2px 5px #eee;"><?php else: ?><div style="height: 100px; background:#f0f0f0; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center;">No Image</div><?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>BG Global:</strong><br><br>
                                            <?php if(!empty($globalSettings['bg_image']) && file_exists(FCPATH . $globalSettings['bg_image'])): ?><img src="<?= base_url($globalSettings['bg_image']); ?>?t=<?= time(); ?>" style="width: 100%; border: 1px solid #ddd; padding: 3px; card-shadow: 2px 2px 5px #eee;"><?php else: ?><div style="height: 100px; background:#f0f0f0; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center;">No Image</div><?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>BG Lampiran:</strong><br><br>
                                            <?php if(!empty($globalSettings['bg_lampiran']) && file_exists(FCPATH . $globalSettings['bg_lampiran'])): ?><img src="<?= base_url($globalSettings['bg_lampiran']); ?>?t=<?= time(); ?>" style="width: 100%; border: 1px solid #ddd; padding: 3px; card-shadow: 2px 2px 5px #eee;"><?php else: ?><div style="height: 100px; background:#f0f0f0; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center;">No Image</div><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hitung total engagement otomatis
        var inputs = document.querySelectorAll('.eng-input');
        inputs.forEach(function(input) { input.addEventListener('input', calculateTotal); });
        function calculateTotal() {
            var total = 0; inputs.forEach(function(input) { total += parseInt(input.value) || 0; });
            document.getElementById('total_engagement').innerText = total;
        }
        
        // Cek URL untuk Tab Aktif
        var urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('tab') === 'daily') {
            $('.nav-tabs a[href="#tab_daily"]').tab('show');
        }
    });

    // FUNGSI PAGINATION MINI (CLIENT SIDE - TAB 1)
    function paginateMini(tableId, direction) {
        var table = document.getElementById(tableId); if(!table) return;
        var rows = table.querySelectorAll('.link-item');
        var container = table.closest('.card');
        var info = container.querySelector('.curr-page');
        
        if(!info) return;

        var totalRows = rows.length; 
        var perPage = 7; // 7 ITEMS PER PAGE
        var totalPages = Math.ceil(totalRows / perPage);
        var currPage = parseInt(info.innerText); 
        var newPage = currPage + direction;
        
        if(newPage < 1 || newPage > totalPages) return;
        
        var start = (newPage - 1) * perPage; 
        var end = start + perPage;
        
        rows.forEach(function(row, index) { 
            if(index >= start && index < end) { 
                row.style.display = 'table-row'; 
            } else { 
                row.style.display = 'none'; 
            } 
        });
        
        info.innerText = newPage;
    }
</script>

