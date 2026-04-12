<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian Medsos</title>
    <style>
        html, body {
            margin: 0; padding: 0; width: 100%; height: 100%;
            font-family: 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
        }
        @media screen {
            body { background-color: #525659; padding: 20px 0; height: auto; }
            .sheet {
                background: white; width: 210mm; height: 297mm;
                margin: 0 auto 20px auto; position: relative;
                card-shadow: 0 0 10px rgba(0,0,0,0.5); overflow: hidden;
            }
        }
        @media print {
            @page { margin: 0px; size: A4 portrait; }
            body { margin: 0px; background-color: white; }
            .sheet {
                position: relative; width: 100%; height: 100%;
                max-height: 296mm; overflow: hidden; page-break-after: always;
                margin: 0; padding: 0;
            }
            .sheet:last-child { page-break-after: auto; }
        }
        .bg-layer {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; background-repeat: no-repeat; background-size: 100% 100%;
        }
        .content-layer { position: relative; z-index: 2; padding: 15mm 10mm; }
        .content-layer-full { position: relative; z-index: 2; padding: 0; width: 100%; height: 100%; }
        .section-top { position: absolute; top: 0; left: 0; width: 100%; }
        .section-middle {
            position: absolute; top: 55%; left: 0; width: 100%;
            transform: translateY(-50%);
        }
        .header-bar {
            background-color: #1F3A60; color: white; width: 100%;
            padding: 20px 0; text-align: center; font-weight: bold;
            font-size: 18px; text-transform: uppercase; margin: 0;
            card-sizing: border-card;
        }
        .sub-header {
            background-color: #1F3A60; color: white; padding: 12px;
            font-weight: bold; text-align: center; font-size: 14px;
            text-transform: uppercase; margin-bottom: 0; letter-spacing: 1px;
        }
        .center-stats-card { width: 85%; margin: 0 auto; padding-top: 40px; }
        .table-title {
            background-color: #1F3A60; color: white; padding: 12px;
            font-weight: bold; text-align: center; border: 1px solid #000;
            border-bottom: none; font-size: 15px; text-transform: uppercase; letter-spacing: 1px;
        }
        .table-stats { width: 100%; border-collapse: collapse; margin-bottom: 0px; border: 1px solid #000; }
        .table-stats th {
            background-color: #1F3A60; color: white; padding: 12px;
            border: 1px solid #fff; text-align: center; font-size: 13px;
        }
        .table-stats th:last-child { border-right: 1px solid #000; }
        .table-stats td {
            border: 1px solid #000; padding: 15px; text-align: center;
            font-weight: bold; background: #fff; font-size: 14px; color: #000;
        }
        .layout-table { width: 100%; border-collapse: collapse; border: none; }
        .layout-table td { vertical-align: top; border: none; padding: 0 5px; }
        .link-list {
            width: 100%; border-collapse: collapse; font-size: 10px;
            background: rgba(255,255,255,0.95); border: 1px solid #ccc;
        }
        .link-list td {
            padding: 5px; border-bottom: 1px solid #ddd; vertical-align: top;
            word-wrap: break-word; word-break: break-all; color: #000;
        }
        .cover-overlay {
            position: absolute; width: 100%; text-align: center; left: 0; z-index: 10;
        }
        .title-area { top: 40%; padding: 0 20px; }
        .date-area { bottom: 25%; }
        .text-yellow {
            color: #FFFF00; font-weight: 900; text-transform: uppercase;
            text-shadow: 2px 2px 4px #000; margin-bottom: 10px;
        }
        .font-big { font-size: 42px; line-height: 1.2; font-family: 'Arial Black', sans-serif; }
        .font-small { font-size: 24px; color: #FFFF00; font-weight: bold; text-shadow: 2px 2px 4px #000; }
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
            <div class="text-yellow font-big" style="margin-top: 50px;"><?= esc($siteName); ?></div>
            <div class="font-small"><?= esc($date_indo); ?></div>
        </div>
    </div>
    <div class="sheet">
        <div class="content-layer-full">
            <div class="section-top">
                <div class="header-bar">JUMLAH POSTINGAN MEDIA SOSIAL</div>
                <div class="center-stats-card">
                    <div class="table-title">POSTINGAN</div>
                    <table class="table-stats">
                        <thead><tr><th style="width: 10%">IG</th><th style="width: 10%">X</th><th style="width: 10%">FB</th><th style="width: 10%">TT</th><th style="width: 10%">PT</th><th style="width: 10%">YT</th><th style="width: 40%">TOTAL</th></tr></thead>
                        <tbody><tr><td><?= $pMap['Instagram']??0; ?></td><td><?= $pMap['Twitter/X']??0; ?></td><td><?= $pMap['Facebook']??0; ?></td><td><?= $pMap['TikTok']??0; ?></td><td><?= ($pMap['PoliceTube']??0) + ($pMap['Lainnya']??0); ?></td><td><?= $pMap['YouTube']??0; ?></td><td><?= $totalPost??0; ?></td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="section-middle">
                <div class="header-bar">JUMLAH ENGAGEMENT MEDIA SOSIAL</div>
                <div class="center-stats-card">
                    <div class="table-title">ENGAGEMENT</div>
                    <table class="table-stats">
                        <thead><tr><th style="width: 10%">IG</th><th style="width: 10%">TW</th><th style="width: 10%">FB</th><th style="width: 10%">TT</th><th style="width: 10%">PT</th><th style="width: 10%">YT</th><th style="width: 40%">TOTAL</th></tr></thead>
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
            <table style="width: 100%; border-collapse: separate; border-spacing: 15px; margin-top: 50px;">
                <?php $counter = 0; foreach($chunk as $img): if($counter % 2 == 0) echo '<tr>'; $imgPath = FCPATH . $img; $imgBase64 = imgToBase64($imgPath); ?>
                    <td style="width: 50%; height: 250px; vertical-align: middle; text-align: center; border: 1px solid #ccc; background: #fff; padding: 5px;">
                        <?php if($imgBase64): ?><img src="<?= $imgBase64; ?>" style="max-width: 100%; max-height: 240px; width: auto; height: auto;"><?php else: ?><span style="color:red; font-size:10px;">Gambar tidak ditemukan</span><?php endif; ?>
                    </td>
                <?php if($counter % 2 != 0) echo '</tr>'; $counter++; endforeach; if($counter % 2 != 0) echo '<td></td></tr>'; ?>
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
            <table class="layout-table" style="margin-top: 100px;">
                <tr>
                    <td style="width: 49%; "><div class="sub-header"><?= strtoupper($plat); ?></div><table class="link-list"><?php $noL = ($pageCount-1)*50 + 1; foreach($colLeft as $url): ?><tr><td style="width: 20px; text-align: center; font-weight:bold;"><?= $noL++; ?>.</td><td><a href="<?= $url; ?>" style="text-decoration:none; color:#000;"><?= $url; ?></a></td></tr><?php endforeach; ?></table></td>
                    <td style="width: 2%;"></td>
                    <td style="width: 49%;"><div class="sub-header"><?= strtoupper($plat); ?></div><table class="link-list"><?php if(!empty($colRight)): $noR = $noL; foreach($colRight as $url): ?><tr><td style="width: 20px; text-align: center; font-weight:bold;"><?= $noR++; ?>.</td><td><a href="<?= $url; ?>" style="text-decoration:none; color:#000;"><?= $url; ?></a></td></tr><?php endforeach; else: ?><tr><td colspan="2" style="text-align:center; padding:10px;">-</td></tr><?php endif; ?></table></td>
                </tr>
            </table>
        </div>
    </div>
    <?php $pageCount++; endforeach; endforeach; ?>
</body>
</html>

