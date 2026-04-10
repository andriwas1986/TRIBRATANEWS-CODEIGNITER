<div class="row">
    <div class="col-sm-12">
        <h1 class="page-title">Laporan YouTube</h1>
        <ol class="breadcrumb">
            <li><a href="<?= adminUrl(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Laporan YouTube</li>
        </ol>
    </div>
</div>

<style>
    /* Styling Tabs */
    .nav-tabs-custom > .nav-tabs { background-color: #cc0000; border-bottom-color: #333; }
    .nav-tabs-custom > .nav-tabs > li.active > a, 
    .nav-tabs-custom > .nav-tabs > li.active > a:hover { 
        border-top-color: #333; border-left-color: #333; border-right-color: #333; 
        color: #cc0000; font-weight: bold; 
    }
    .nav-tabs-custom > .nav-tabs > li > a { color: #fff; font-weight: 600; }
    .nav-tabs-custom > .nav-tabs > li > a:hover { color: #333; }

    /* --- TABEL COMPACT / MEPET --- */
    .table-compact-yt { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0 2px; 
        margin-top: 5px;
    }
    
    .table-compact-yt thead th { 
        background-color: #cc0000; 
        color: white; 
        padding: 8px 5px; 
        text-transform: uppercase; 
        font-size: 12px; 
        border: none;
        font-weight: 700;
        text-align: center !important; 
        vertical-align: middle;
    }
    .table-compact-yt thead th:first-child { border-radius: 4px 0 0 4px; }
    .table-compact-yt thead th:last-child { border-radius: 0 4px 4px 0; }

    .table-compact-yt tbody tr { 
        background-color: #fff; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); 
    }
    .table-compact-yt tbody tr:hover { background-color: #fce8e8; }

    .table-compact-yt td { 
        padding: 5px 8px; 
        border: none; 
        vertical-align: middle; 
        color: #333;
        font-size: 13px;
        line-height: 1.2; 
        border-bottom: 1px solid #f0f0f0;
    }

    /* Kolom NO */
    .table-compact-yt td:first-child { 
        text-align: center; 
        font-weight: bold; 
        color: #cc0000;
        border-left: 3px solid #cc0000; 
        border-radius: 4px 0 0 4px;
    }
    
    /* Kolom SATKER */
    .col-satker {
        font-weight: bold;
        color: #1F3A60; 
        text-transform: uppercase;
        font-size: 11px; 
        text-align: center; 
        white-space: nowrap; 
    }

    /* Kolom LINK */
    .table-compact-yt td:last-child { 
        border-radius: 0 4px 4px 0; 
        border-right: 1px solid #eee;
    }

    .yt-link-text {
        text-decoration: none;
        color: #444;
        font-weight: 500;
        display: block;
        font-family: sans-serif;
        white-space: nowrap; 
        overflow: hidden;
        text-overflow: ellipsis; 
        max-width: 550px; 
    }
    .yt-link-text:hover { color: #cc0000; text-decoration: underline; }
    
    .badge-category {
        font-size: 8px; 
        padding: 1px 4px; 
        border-radius: 3px; 
        margin-right: 5px;
        text-transform: uppercase;
        vertical-align: middle;
        color: white;
        display: inline-block;
        font-weight: bold;
    }
    .cat-shorts { background-color: #ff0000; }
    .cat-video { background-color: #555; }
    .cat-live { background-color: #990000; }

    .pagination { margin: 10px 0 0 0; display: flex; justify-content: flex-end; }
    .pagination li a { 
        color: #cc0000; border: 1px solid #ddd; padding: 5px 10px; font-size: 12px; margin-left: 2px; border-radius: 3px; text-decoration: none;
    }
    .pagination li.active a { 
        background-color: #cc0000; border-color: #cc0000; color: white; 
    }
    .pagination li.disabled a { color: #ccc; pointer-events: none; }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= (!isset($_GET['tab']) || $_GET['tab'] == 'daily') ? 'active' : ''; ?>">
                    <a href="#tab_daily" data-toggle="tab"><i class="fa fa-list"></i> Daftar Konten Harian</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'range') ? 'active' : ''; ?>">
                    <a href="#tab_range" data-toggle="tab"><i class="fa fa-calendar-check-o"></i> Rekapan (Range)</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>">
                    <a href="#tab_monthly" data-toggle="tab"><i class="fa fa-calendar"></i> Laporan Bulanan</a>
                </li>
            </ul>
            
            <div class="tab-content" style="background: #f4f6f9; padding: 15px;">
                
                <div class="tab-pane <?= (!isset($_GET['tab']) || $_GET['tab'] == 'daily') ? 'active' : ''; ?>" id="tab_daily">
                    
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <form action="<?= current_url(); ?>" method="get" class="form-inline">
                                <input type="hidden" name="tab" value="daily">
                                <div class="form-group">
                                    <label class="m-r-5" style="color:#555; font-size:12px;">PILIH TANGGAL:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="<?= esc($selectedDate); ?>" onchange="this.form.submit()" style="font-weight:bold; border: 1px solid #cc0000; color: #cc0000;">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?= adminUrl('smart-report/youtube/export_excel?date='.$selectedDate); ?>" target="_blank" class="btn btn-success btn-sm btn-flat">
                                <i class="fa fa-file-excel-o"></i> DOWNLOAD EXCEL
                            </a>
                        </div>
                    </div>

                    <div class="box box-solid" style="background: transparent; box-shadow: none; border: none;">
                        <div class="box-body no-padding">
                            <table class="table-compact-yt">
                                <thead>
                                    <tr>
                                        <th width="40">NO</th>
                                        <th width="200">SATKER</th>
                                        <th>LINK YOUTUBE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($dailyLinks)): foreach($dailyLinks as $det): ?>
                                        <?php 
                                            $catClass = 'cat-video'; 
                                            if($det['category']=='Shorts') { $catClass='cat-shorts'; }
                                            if($det['category']=='Live')   { $catClass='cat-live'; }
                                            
                                            $isPolres = (stripos($det['satker'], 'POLRES') !== false || stripos($det['satker'], 'POLRESTA') !== false);
                                            $satkerColor = $isPolres ? '#1F3A60' : '#555';
                                            $satkerIcon  = $isPolres ? '<i class="fa fa-shield text-primary"></i> ' : '';
                                            $rowBg = $isPolres ? 'background-color: #fdfdfd;' : ''; 
                                        ?>
                                        <tr style="<?= $rowBg; ?>">
                                            <td><?= $det['no_urut']; ?></td>
                                            <td class="col-satker" style="color: <?= $satkerColor; ?>;">
                                                <?= $satkerIcon . esc($det['satker']); ?>
                                            </td>
                                            <td>
                                                <a href="<?= esc($det['url']); ?>" target="_blank" class="yt-link-text">
                                                    <span class="badge-category <?= $catClass; ?>"><?= $det['category']; ?></span>
                                                    <?= esc($det['url']); ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center" style="padding: 20px; color: #999; font-size:12px;">
                                                Belum ada konten YouTube untuk tanggal ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="box-footer" style="background: transparent; border-top: none; padding-top: 5px;">
                            <div class="row">
                                <div class="col-md-6" style="padding-top: 5px; color: #777; font-size: 11px;">
                                    Total Data: <b><?= number_format($totalDaily ?? 0); ?></b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <?= $pager; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'range') ? 'active' : ''; ?>" id="tab_range">
                    <form action="<?= current_url(); ?>" method="get" class="well form-inline" style="background: #fff; border-top: 3px solid #cc0000; padding:10px;">
                        <input type="hidden" name="tab" value="range">
                        <div class="form-group"><label>Dari:</label><input type="date" name="start_date" class="form-control input-sm" value="<?= esc($startDate); ?>"></div>
                        <div class="form-group m-l-10"><label>Sampai:</label><input type="date" name="end_date" class="form-control input-sm" value="<?= esc($endDate); ?>"></div>
                        
                        <button type="submit" class="btn btn-danger btn-sm m-l-10"><i class="fa fa-search"></i> Tampilkan</button>
                        
                        <a href="<?= adminUrl('smart-report/youtube/export_excel?start_date='.$startDate.'&end_date='.$endDate); ?>" target="_blank" class="btn btn-success btn-sm m-l-5">
                            <i class="fa fa-file-excel-o"></i> Download Excel
                        </a>
                    </form>

                    <?php if(isset($rangeLinks)): ?>
                        <div class="box box-solid m-t-10">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th style="width: 10px">No</th>
                                            <th>Tanggal</th>
                                            <th>Satker</th>
                                            <th>Kategori</th>
                                            <th>URL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($rangeLinks)): $no=1; foreach($rangeLinks as $r): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d/m/Y', strtotime($r['created_at'])); ?></td>
                                            <td style="font-weight:bold; font-size:11px;"><?= esc($r['satker']); ?></td>
                                            <td><span class="label label-danger btn-xs"><?= $r['category']; ?></span></td>
                                            <td style="font-size:11px;"><a href="<?= $r['url']; ?>" target="_blank"><?= $r['url']; ?></a></td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>" id="tab_monthly">
                    <form action="<?= current_url(); ?>" method="get" class="form-inline m-b-15"><input type="hidden" name="tab" value="monthly"><div class="form-group"><label class="m-r-5">Bulan:</label><select name="month" class="form-control input-sm"><?php $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; foreach($months as $k => $v): ?><option value="<?= $k; ?>" <?= ($k == $selectedMonth) ? 'selected' : ''; ?>><?= $v; ?></option><?php endforeach; ?></select></div><div class="form-group"><label class="m-r-5 m-l-5">Tahun:</label><select name="year" class="form-control input-sm"><?php for($y=date('Y'); $y>=2020; $y--): ?><option value="<?= $y; ?>" <?= ($y == $selectedYear) ? 'selected' : ''; ?>><?= $y; ?></option><?php endfor; ?></select></div><button type="submit" class="btn btn-danger btn-sm m-l-10"><i class="fa fa-eye"></i> Filter</button></form>
                    <div class="row"><div class="col-md-6"><div class="small-box bg-red"><div class="inner"><h3><?= $monthlyTotal ?? 0; ?></h3><p>Total Link Bulan Ini</p></div><div class="icon"><i class="fa fa-youtube-play"></i></div></div></div></div>
                </div>

            </div>
        </div>
    </div>
</div>