<?php
// --- HELPER TANGGAL INDONESIA (Langsung di View) ---
$engMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$indMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$d = date('d', strtotime($selectedDate));
$m = date('F', strtotime($selectedDate)); // Nama Bulan Inggris
$y = date('Y', strtotime($selectedDate));

// Ganti Bulan Inggris ke Indonesia
$mIndo = str_replace($engMonths, $indMonths, $m); 

// Format Final: "Periode : 17 Januari 2026"
$tanggalLengkap = "Periode : " . $d . " " . $mIndo . " " . $y;
?>

<div class="row">
    <div class="col-sm-12">
        <h1 class="page-title" style="font-weight: 800; font-size: 35px; margin-bottom: 5px;">
            SMART TBNEWS
            <small style="display: block; font-size: 14px; margin-top: 5px; color: #555; font-weight: normal;">
                (System Monitoring & Archive Rekapitulasi Tribratanews)
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= adminUrl(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tb News</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 m-b-15">
        <div style="background: linear-gradient(90deg, #8E2DE2 0%, #4A00E0 100%); 
                    color: #fff; 
                    padding: 8px 15px; 
                    border-radius: 4px; 
                    card-shadow: 0 2px 4px rgba(0,0,0,0.15);">
            <marquee behavior="scroll" direction="left" scrollamount="6" 
                     style="font-family: sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.5px;">
                Selamat datang di System Monitoring & Archive Rekapitulasi Tribratanews - SMART TBNEWS |  Rekapitulasi Tribratanews Digital | Data Aman & Terpadu | Kerja Cepat, Laporan Tepat
            </marquee>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= (!isset($_GET['tab']) || $_GET['tab'] == 'daily') ? 'active' : ''; ?>">
                    <a href="#tab_daily" data-toggle="tab"><i class="fa fa-bar-chart"></i> Laporan Harian</a>
                </li>
                <li class="<?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>">
                    <a href="#tab_monthly" data-toggle="tab"><i class="fa fa-calendar"></i> Rekapitulasi Bulanan</a>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane <?= (!isset($_GET['tab']) || $_GET['tab'] == 'daily') ? 'active' : ''; ?>" id="tab_daily">
                    <div class="row m-b-15">
                        <div class="col-sm-12">
                            <form action="<?= current_url(); ?>" method="get" class="form-inline">
                                <div class="form-group">
                                    <label class="control-label m-r-5">Pilih Tanggal:</label>
                                    <input type="date" name="date" class="form-control input-sm" value="<?= esc($selectedDate); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm m-l-10"><i class="fa fa-search"></i> Tampilkan</button>
                                <a href="<?= current_url(); ?>" class="btn btn-default btn-sm m-l-5">Hari Ini</a>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header with-border text-center">
                                    <h3 class="card-title" style="line-height: 1.5; font-weight: 800; display: block;">
                                        GRAFIK PRODUK HARIAN TRIBRATANEWS<br>
                                        <small style="font-size: 13px; color: #333; font-weight: normal;">
                                            <?= $tanggalLengkap; ?>
                                        </small>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper" style="width: 100%; overflow-x: hidden;">
                                        <div class="chart-container" style="position: relative; width: 100%;">
                                            <canvas id="operatorChart"></canvas>
                                        </div>
                                    </div>
                                    <div id="chartError" style="display:none; text-align:center; color:red; padding-top:20px;">
                                        <i class="fa fa-warning"></i> Gagal memuat Grafik.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-success">
                                <div class="card-header with-border">
                                    <h3 class="card-title">Laporan WhatsApp</h3>
                                    <div class="card-tools float-end">
                                        <button type="button" class="btn btn-xs btn-default" onclick="toggleWaSettings()" title="Ganti Nama Polres">
                                            <i class="fa fa-cog"></i> Setting Header
                                        </button>
                                        <button type="button" class="btn btn-xs btn-success" onclick="copyToClipboard()">
                                            <i class="fa fa-copy"></i> Salin Teks
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="waSettingsBox" style="display:none; background: #f0f0f0; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #ddd;">
                                        <div class="form-group m-b-5">
                                            <label style="font-size:10px;">Nama Satker (Header):</label>
                                            <input type="text" id="waInputSatker" class="form-control input-sm" placeholder="Contoh: POLRES MADIUN" onkeyup="updateWaText()">
                                        </div>
                                        <div class="form-group m-b-5">
                                            <label style="font-size:10px;">Kepada (Yth):</label>
                                            <input type="text" id="waInputKepada" class="form-control input-sm" placeholder="Contoh: Kabid Humas Polda Jatim" onkeyup="updateWaText()">
                                        </div>
                                        <div class="form-group m-b-0">
                                            <label style="font-size:10px;">Tembusan:</label>
                                            <input type="text" id="waInputTembusan" class="form-control input-sm" placeholder="Contoh: Kapolres Madiun" onkeyup="updateWaText()">
                                        </div>
                                        <small class="text-muted" style="font-size: 9px;">*Settingan tersimpan otomatis di browser.</small>
                                    </div>

                                    <textarea id="waReportText" class="form-control" rows="28" style="font-family: monospace; font-size: 11px; white-space: pre; background: #f9f9f9;" readonly><?= esc($waText); ?></textarea>
                                    
                                    <div id="rawWaContent" style="display:none;"><?= esc($waText); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane <?= (isset($_GET['tab']) && $_GET['tab'] == 'monthly') ? 'active' : ''; ?>" id="tab_monthly">
                    <div class="row m-b-15">
                        <div class="col-sm-12">
                            <form action="<?= current_url(); ?>" method="get" class="form-inline" style="display:inline-block;">
                                <input type="hidden" name="tab" value="monthly">
                                <div class="form-group">
                                    <label class="m-r-5">Bulan:</label>
                                    <select name="month" class="form-control input-sm">
                                        <?php $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                        foreach($months as $key => $val): ?>
                                            <option value="<?= $key; ?>" <?= ($key == (int)$selectedMonth) ? 'selected' : ''; ?>><?= $val; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group m-l-10">
                                    <label class="m-r-5">Tahun:</label>
                                    <select name="year" class="form-control input-sm">
                                        <?php for($y=date('Y'); $y>=2020; $y--): ?>
                                            <option value="<?= $y; ?>" <?= ($y == $selectedYear) ? 'selected' : ''; ?>><?= $y; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm m-l-10"><i class="fa fa-eye"></i> Filter</button>
                            </form>

                            <div class="float-end">
                                <a href="<?= base_url(adminUrl() . '/tb-news/export_excel?month='.$selectedMonth.'&year='.$selectedYear); ?>" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                                <button onclick="printDiv('printableArea')" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> Export PDF (Print)</button>
                            </div>
                        </div>
                    </div>

                    <div id="printableArea">
                        <div class="visible-print-block text-center m-b-20">
                            <h3>REKAPITULASI VIRALISASI TB NEWS</h3>
                            <h4>PERIODE: <?= strtoupper($months[(int)$selectedMonth]); ?> <?= $selectedYear; ?></h4>
                            <hr>
                        </div>

                        <div class="card card-info">
                            <div class="card-header with-border">
                                <h3 class="card-title">1. Matriks Keaktifan Operator</h3>
                            </div>
                            <div class="card-body table-responsive no-padding">
                                <table class="table table-bordered table-striped table-hover table-sm text-center" style="font-size: 11px;">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th style="text-align:left; width:150px;">NAMA OPERATOR</th>
                                            <?php $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$selectedMonth, $selectedYear);
                                            for($d=1; $d<=$daysInMonth; $d++): ?>
                                                <th style="min-width:20px; padding:2px;"><?= $d; ?></th>
                                            <?php endfor; ?>
                                            <th class="bg-info">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($monthlyRecap)): ?>
                                            <?php foreach($monthlyRecap as $row): $totalBulanan = 0; ?>
                                                <tr>
                                                    <td style="font-weight:bold; text-align:left;"><?= esc($row['name']); ?></td>
                                                    <?php for($d=1; $d<=$daysInMonth; $d++): 
                                                        $count = isset($row['days'][$d]) ? $row['days'][$d] : 0;
                                                        $totalBulanan += $count;
                                                        $bgStyle = ($count > 0) ? 'background-color:#dff0d8; font-weight:bold;' : 'color:#ccc;';
                                                    ?>
                                                        <td style="<?= $bgStyle; ?>"><?= ($count > 0) ? $count : '.'; ?></td>
                                                    <?php endfor; ?>
                                                    <td class="bg-info" style="font-weight:bold;"><?= $totalBulanan; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="<?= $daysInMonth + 2; ?>">Belum ada data.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card card-warning">
                            <div class="card-header with-border">
                                <h3 class="card-title">2. Rincian Daftar Link Berita</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" style="font-size: 12px;">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th style="width: 40px; text-align:center;">No</th>
                                            <th class="col-gambar text-center">Gambar</th>
                                            <th style="width: 120px;">Tanggal & Jam</th>
                                            <th style="width: 150px;">Operator</th>
                                            <th>Judul Berita & Link</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($monthlyLinks)): ?>
                                            <?php $no=1; foreach($monthlyLinks as $link): 
                                                $imgSrc = "";
                                                if (!empty($link['image_mid'])) {
                                                     $storage = isset($link['storage']) ? $link['storage'] : 'local';
                                                     if (function_exists('getBaseURLByStorage')) {
                                                         $imgSrc = getBaseURLByStorage($storage) . $link['image_mid'];
                                                     } else {
                                                         $imgSrc = base_url($link['image_mid']);
                                                     }
                                                } elseif (!empty($link['image_url'])) {
                                                    $imgSrc = $link['image_url'];
                                                }
                                            ?>
                                            <tr>
                                                <td style="text-align:center; vertical-align: middle;"><?= $no++; ?></td>
                                                <td class="thumb-td text-center" style="vertical-align: middle;">
                                                    <?php if(!empty($imgSrc)): ?>
                                                        <img src="<?= $imgSrc; ?>" class="thumb-img">
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size:10px;">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="vertical-align: middle;"><?= date('d/m/Y H:i', strtotime($link['created_at'])); ?></td>
                                                <td style="vertical-align: middle; font-weight:bold; color:#007bff;"><?= esc($link['username']); ?></td>
                                                <td style="vertical-align: middle;">
                                                    <span style="font-weight:600; display:block; margin-bottom:3px;"><?= esc($link['title']); ?></span>
                                                    <a href="<?= base_url($link['slug']); ?>" target="_blank" style="color:#28a745; font-size:11px; text-decoration:none;">
                                                        <i class="fa fa-link"></i> <?= base_url($link['slug']); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center" style="padding:20px;">Tidak ada link berita bulan ini.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/admin/js/Chart.min.js'); ?>"></script>

<script>
    // --- 1. LOGIC SETTING WA (AUTO SAVE) ---
    function toggleWaSettings() {
        var card = document.getElementById("waSettingsBox");
        card.style.display = (card.style.display === "none") ? "block" : "none";
    }

    function updateWaText() {
        var satker = document.getElementById("waInputSatker").value;
        var kepada = document.getElementById("waInputKepada").value;
        var tembusan = document.getElementById("waInputTembusan").value;

        // Simpan ke Browser
        localStorage.setItem("wa_satker", satker);
        localStorage.setItem("wa_kepada", kepada);
        localStorage.setItem("wa_tembusan", tembusan);

        // Ambil Data Mentah
        var rawText = document.getElementById("rawWaContent").textContent;
        var splitKey = "DARI :"; // Pemisah antara Header lama dan Isi
        var parts = rawText.split(splitKey);
        
        if (parts.length > 1) {
            var bodyContent = splitKey + parts[1]; // Ambil isi (tetap)
            
            // Header Baru
            var newHeader = "*" + satker.toUpperCase() + "*\n";
            newHeader += "Kepada : Yth. " + kepada + "\n";
            newHeader += "Tembusan : " + tembusan + "\n\n";

            document.getElementById("waReportText").value = newHeader + bodyContent;
        }
    }

    // --- 2. JALANKAN SAAT LOAD ---
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil Settingan Tersimpan
        var savedSatker = localStorage.getItem("wa_satker") || "POLRES MADIUN";
        var savedKepada = localStorage.getItem("wa_kepada") || "Kabid Humas Polda Jatim";
        var savedTembusan = localStorage.getItem("wa_tembusan") || "Kapolres Madiun";

        document.getElementById("waInputSatker").value = savedSatker;
        document.getElementById("waInputKepada").value = savedKepada;
        document.getElementById("waInputTembusan").value = savedTembusan;

        // Apply Settingan
        updateWaText();
    });


    // --- 3. UTILITY FUNCTIONS ---
    function copyToClipboard() {
        var copyText = document.getElementById("waReportText");
        copyText.select();
        try { document.execCommand('copy'); alert("Laporan berhasil disalin!"); } catch (err) { alert('Gagal.'); }
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }


    // --- 4. CHART JS LOGIC ---
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof Chart === 'undefined') {
            document.getElementById('operatorChart').style.display = 'none';
            document.getElementById('chartError').style.display = 'block';
            document.getElementById('chartError').innerHTML = 
                '<i class="fa fa-times-circle"></i> Gagal memuat file <b>Chart.min.js</b>.<br>' +
                '<small>Pastikan file ada di folder: <code>assets/admin/js/</code></small>';
            return;
        }

        // Global Settings (HD & Black Font)
        Chart.defaults.global.defaultFontFamily = "'Arial', 'Helvetica', sans-serif";
        Chart.defaults.global.defaultFontSize = 12;
        Chart.defaults.global.defaultFontColor = '#000000';
        Chart.defaults.global.devicePixelRatio = window.devicePixelRatio || 1; 

        var chartLabels = <?php echo $chartLabels; ?>;
        var chartData = <?php echo $chartData; ?>;

        if(chartLabels && chartLabels.length > 0) {
            
            // Tinggi Grafik Compact (Rapat)
            var dataCount = chartLabels.length;
            var fixedHeight = (dataCount * 28) + 80; 
            if (fixedHeight < 250) fixedHeight = 250; 

            var chartContainer = document.querySelector('.chart-container');
            chartContainer.style.height = fixedHeight + 'px'; 

            var ctx = document.getElementById('operatorChart').getContext('2d');
            
            // Warna Merah Utama vs Pudar
            var backgroundColors = chartData.map(function(value, index) {
                return index === 0 ? 'rgba(221, 75, 57, 1)' : 'rgba(221, 75, 57, 0.6)';
            });

            new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Berita',
                        data: chartData,
                        backgroundColor: backgroundColors,
                        borderColor: '#d73925',
                        borderWidth: 1,
                        // Batang Ramping
                        barThickness: 12,
                        maxBarThickness: 15,
                        minBarLength: 2 
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false, 
                    // Animasi Smooth
                    animation: {
                        duration: 1500, 
                        easing: 'easeOutQuart'
                    },
                    hover: { animationDuration: 400 },

                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                                fontColor: '#000000',
                                fontSize: 11,
                                fontStyle: 'bold'
                            },
                            gridLines: { color: "#eee" }
                        }],
                        yAxes: [{
                            ticks: {
                                autoSkip: false,
                                fontColor: '#000000', 
                                fontSize: 12,         
                                fontStyle: 'bold',    
                                padding: 5 
                            },
                            gridLines: { display: false }
                        }]
                    },
                    legend: { display: false },
                    
                    // TITLE DIMATIKAN DI SINI (Karena sudah ada di HTML)
                    title: { display: false } 
                }
            });
        } else {
            var ctx = document.getElementById('operatorChart');
            ctx.style.display = 'none';
            var container = document.querySelector('.chart-container');
            container.innerHTML += '<p class="text-center text-muted" style="padding-top:100px;">Belum ada data berita untuk ditampilkan.</p>';
        }
    });
</script>

<style>
    .col-gambar { width: 100px; }
    .thumb-img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    @media print {
        @page { size: landscape; margin: 0.5cm; }
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea { position: absolute; left: 0; top: 0; width: 100%; }

        /* Print Full Width Images */
        .col-gambar { width: 25% !important; }
        .thumb-td { padding: 2px !important; vertical-align: middle !important; }
        .thumb-img {
            width: 100% !important;
            height: auto !important;
            max-width: none !important;
            object-fit: contain !important;
            border: none !important;
            display: block;
            margin: 0 auto;
        }
        
        /* Cleanup */
        .card { border: 1px solid #ccc !important; card-shadow: none !important; }
        a[href]:after { content: none !important; }
        .btn, .form-control { display: none !important; }
    }
</style>

