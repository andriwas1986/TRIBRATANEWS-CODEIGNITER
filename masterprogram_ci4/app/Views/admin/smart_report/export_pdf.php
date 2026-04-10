<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian Medsos</title>
    <style>
        /* ============================================================
           1. SETUP GLOBAL & RESET
           ============================================================ */
        html, body {
            margin: 0; padding: 0; width: 100%; height: 100%;
            font-family: 'Arial', sans-serif; background-color: #fff;
        }

        /* Container Halaman A4 */
        .sheet {
            position: relative; width: 100%; height: 100%;
            max-height: 297mm; overflow: hidden; page-break-after: always;
            margin: 0; padding: 0;
        }
        .sheet:last-child { page-break-after: auto; }

        /* ============================================================
           2. BACKGROUND & LAYERS
           ============================================================ */
        .bg-layer {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; background-repeat: no-repeat; background-size: 100% 100%;
        }
        .content-layer { position: relative; z-index: 2; padding: 15mm 10mm; }
        .content-layer-full { position: relative; z-index: 2; padding: 0; width: 100%; height: 100%; }

        /* ============================================================
           3. POSISI KONTEN (Layout)
           ============================================================ */
        .section-top { position: absolute; top: 0; left: 0; width: 100%; height: 50%; }
        /* Posisi Tengah Fixed Pixel untuk Dompdf */
        .section-middle { position: absolute; top: 540px; left: 0; width: 100%; height: 50%; }

        /* Header Style */
        .header-bar {
            background-color: #1F3A60; color: white; width: 100%;
            padding: 15px 0; text-align: center; font-weight: bold;
            font-size: 18px; text-transform: uppercase; margin: 0;
        }
        .sub-header {
            background-color: #1F3A60; color: white; padding: 5px;
            font-weight: bold; text-align: center; font-size: 12px;
            text-transform: uppercase; margin-bottom: 0; border: 1px solid #ccc;
        }

        /* Statistik Tables */
        .center-stats-box { width: 85%; margin: 0 auto; padding-top: 30px; }
        .table-title {
            background-color: #1F3A60; color: white; padding: 8px;
            font-weight: bold; text-align: center; border: 1px solid #000;
            border-bottom: none; font-size: 14px; text-transform: uppercase;
        }
        .table-stats { width: 100%; border-collapse: collapse; border: 1px solid #000; }
        .table-stats th {
            background-color: #1F3A60; color: white; padding: 8px;
            border: 1px solid #fff; text-align: center; font-size: 12px;
        }
        .table-stats td {
            border: 1px solid #000; padding: 8px; text-align: center;
            font-weight: bold; background: #fff; font-size: 12px; color: #000;
        }

        /* ============================================================
           4. PERBAIKAN TABEL LINK (ANTI MELEBAR)
           ============================================================ */
        .layout-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: none; 
            table-layout: fixed; 
        }
        .layout-table td { 
            vertical-align: top; 
            border: none; 
            padding: 0 5px; 
        }

        .link-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            background: rgba(255,255,255,0.95);
            border: 1px solid #ccc;
            table-layout: fixed; /* Memaksa layout mengikuti width kolom */
        }
        
        .link-list td {
            padding: 4px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
            color: #000;
            
            /* PENTING: Memaksa URL panjang turun ke bawah */
            word-wrap: break-word;
            word-break: break-all; 
            white-space: normal;
        }

        /* Cover & Text */
        .cover-overlay { position: absolute; width: 100%; text-align: center; left: 0; z-index: 10; }
        .title-area { top: 35%; padding: 0 20px; }
        .text-yellow { color: #FFFF00; font-weight: 900; text-transform: uppercase; margin-bottom: 10px; }
        .font-big { font-size: 42px; line-height: 1.2; font-family: 'Helvetica', 'Arial Black', sans-serif; font-weight: bold; }
        .font-small { font-size: 24px; color: #FFFF00; font-weight: bold; }
        
        @page { margin: 0px; size: A4 portrait; }
    </style>
</head>
<body>
    <?php
    function imgToBase64($path) {
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } return '';
    }
    $bgGlobalPath = FCPATH . ($global['bg_image'] ?? '');
    $bgLampPath   = FCPATH . ($global['bg_lampiran'] ?? ($global['bg_image'] ?? ''));
    $bgCoverPath  = FCPATH . ($global['cover_image'] ?? '');
    $bgGlobalBase64 = imgToBase64($bgGlobalPath);
    $bgLampBase64   = imgToBase64($bgLampPath);
    $bgCoverBase64  = imgToBase64($bgCoverPath);
    ?>

    <div class="sheet">
        <?php if($bgCoverBase64): ?><div class="bg-layer" style="background-image: url('<?= $bgCoverBase64; ?>');"></div><?php else: ?><div class="bg-layer" style="background-color: #003366;"></div><?php endif; ?>
        <div class="cover-overlay title-area">
            <div class="text-yellow font-big">LAPORAN VIRALISASI</div>
            <div class="text-yellow font-big">PRODUK</div>
            <div class="text-yellow font-big" style="margin-top: 50px;"><?= esc($siteName); ?></div><br>
            <div class="font-small"><?= esc($date_indo); ?></div>
        </div>
    </div>

    <div class="sheet">
        <?php if($bgGlobalBase64): ?> <?php endif; ?>
        <div class="content-layer-full">
            <div class="section-top">
                <div class="header-bar">JUMLAH POSTINGAN MEDIA SOSIAL</div>
                <div class="center-stats-box">
                    <div class="table-title">POSTINGAN</div>
                    <table class="table-stats" width="100%">
                        <thead><tr><th width="10%">IG</th><th width="10%">X</th><th width="10%">FB</th><th width="10%">TT</th><th width="10%">PT</th><th width="10%">YT</th><th width="40%">TOTAL</th></tr></thead>
                        <tbody><tr><td><?= $pMap['Instagram']??0; ?></td><td><?= $pMap['Twitter/X']??0; ?></td><td><?= $pMap['Facebook']??0; ?></td><td><?= $pMap['TikTok']??0; ?></td><td><?= ($pMap['PoliceTube']??0) + ($pMap['Lainnya']??0); ?></td><td><?= $pMap['YouTube']??0; ?></td><td><?= $totalPost??0; ?></td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="section-middle">
                <div class="header-bar">JUMLAH ENGAGEMENT MEDIA SOSIAL</div>
                <div class="center-stats-box">
                    <div class="table-title">ENGAGEMENT</div>
                    <table class="table-stats" width="100%">
                        <thead><tr><th width="10%">IG</th><th width="10%">TW</th><th width="10%">FB</th><th width="10%">TT</th><th width="10%">PT</th><th width="10%">YT</th><th width="40%">TOTAL</th></tr></thead>
                        <tbody><tr><td><?= number_format($stats['ig']??0); ?></td><td><?= number_format($stats['tw']??0); ?></td><td><?= number_format($stats['fb']??0); ?></td><td><?= number_format($stats['tt']??0); ?></td><td><?= number_format($stats['pt']??0); ?></td><td><?= number_format($stats['yt']??0); ?></td><td><?= number_format($stats['total']??0); ?></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if(!empty($stats['content_images'])): $images = json_decode($stats['content_images'], true); if(is_array($images) && count($images) > 0): $chunks = array_chunk($images, 6); foreach($chunks as $chunk): ?>
    <div class="sheet">
        <?php if($bgLampBase64): ?><div class="bg-layer" style="background-image: url('<?= $bgLampBase64; ?>');"></div><?php endif; ?>
        <div class="content-layer">
            <table width="100%" style="border-collapse: separate; border-spacing: 15px; margin-top: 50px;">
                <?php $counter = 0; foreach($chunk as $img): if($counter % 2 == 0) echo '<tr>'; $imgPath = FCPATH . $img; $imgBase64 = imgToBase64($imgPath); ?>
                    <td width="50%" height="250" style="vertical-align: middle; text-align: center; border: 1px solid #ccc; background: #fff; padding: 5px;">
                        <?php if($imgBase64): ?><img src="<?= $imgBase64; ?>" style="max-width: 100%; max-height: 240px;"><?php else: ?><span style="color:red; font-size:10px;">Gambar tidak ditemukan</span><?php endif; ?>
                    </td>
                <?php if($counter % 2 != 0) echo '</tr>'; $counter++; endforeach; if($counter % 2 != 0) echo '<td width="50%"></td></tr>'; ?>
            </table>
        </div>
    </div>
    <?php endforeach; endif; endif; ?>

    <?php 
    $linksByPlatform = []; if(!empty($links)) { foreach($links as $l) { $p = ($l['platform']=='Lainnya') ? 'PoliceTube' : $l['platform']; $linksByPlatform[$p][] = $l['url']; } }
    foreach($linksByPlatform as $plat => $urls): $chunks = array_chunk($urls, 50); $pageCount = 1; foreach($chunks as $pageLinks): $colLeft = array_slice($pageLinks, 0, 25); $colRight = array_slice($pageLinks, 25, 25);
    ?>
    <div class="sheet">
        <?php if($bgGlobalBase64): ?><div class="bg-layer" style="background-image: url('<?= $bgGlobalBase64; ?>');"></div><?php endif; ?>
        <div class="content-layer">
            <table class="layout-table" width="100%" style="margin-top: 80px;">
                <tr>
                    <td width="48%" valign="top">
                        <div class="sub-header"><?= strtoupper($plat); ?></div>
                        <table class="link-list" width="100%">
                            <?php $noL = ($pageCount-1)*50 + 1; foreach($colLeft as $url): ?>
                            <tr>
                                <td width="8%" align="center" style="font-weight:bold;"><?= $noL++; ?>.</td>
                                <td width="92%" align="left"><a href="<?= $url; ?>" style="text-decoration:none; color:#000;"><?= $url; ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>

                    <td width="4%"></td>

                    <td width="48%" valign="top">
                        <div class="sub-header"><?= strtoupper($plat); ?></div>
                        <table class="link-list" width="100%">
                            <?php if(!empty($colRight)): $noR = $noL; foreach($colRight as $url): ?>
                            <tr>
                                <td width="8%" align="center" style="font-weight:bold;"><?= $noR++; ?>.</td>
                                <td width="92%" align="left"><a href="<?= $url; ?>" style="text-decoration:none; color:#000;"><?= $url; ?></a></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="2" style="text-align:center; padding:10px;">-</td></tr>
                            <?php endif; ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php $pageCount++; endforeach; endforeach; ?>

</body>
</html>