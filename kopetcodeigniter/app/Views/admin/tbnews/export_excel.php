<!DOCTYPE html>
<html>
<head>
    <title>Rekap Tb News</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-left { text-align: left; }
        .bg-success { background-color: #dff0d8; }
        .header-title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .header-subtitle { font-size: 14px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $namaBulan = isset($months[(int)$selectedMonth]) ? strtoupper($months[(int)$selectedMonth]) : '';
    ?>

    <div class="header-title">REKAPITULASI VIRALISASI TB NEWS</div>
    <div class="header-subtitle">PERIODE: <?= $namaBulan; ?> <?= $selectedYear; ?></div>

    <h4>1. Matriks Keaktifan Operator</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 200px;">NAMA OPERATOR</th>
                <?php 
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$selectedMonth, $selectedYear);
                for($d=1; $d<=$daysInMonth; $d++): ?>
                    <th style="width: 25px;"><?= $d; ?></th>
                <?php endfor; ?>
                <th style="background-color: #d9edf7;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($monthlyRecap)): ?>
                <?php foreach($monthlyRecap as $row): $totalBulanan = 0; ?>
                    <tr>
                        <td class="text-left"><strong><?= esc($row['name']); ?></strong></td>
                        <?php for($d=1; $d<=$daysInMonth; $d++): 
                            $count = isset($row['days'][$d]) ? $row['days'][$d] : 0;
                            $totalBulanan += $count;
                            $bg = ($count > 0) ? 'background-color:#dff0d8;' : '';
                        ?>
                            <td style="<?= $bg; ?>"><?= ($count > 0) ? $count : ''; ?></td>
                        <?php endfor; ?>
                        <td style="background-color: #d9edf7; font-weight:bold;"><?= $totalBulanan; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="<?= $daysInMonth + 2; ?>">Belum ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>

    <h4>2. Rincian Daftar Link Berita</h4>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 130px;">Tanggal & Jam</th>
                <th style="width: 200px;">Operator</th>
                <th style="width: 400px;">Judul Berita</th>
                <th style="width: 400px;">Link URL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($monthlyLinks)): ?>
                <?php $no=1; foreach($monthlyLinks as $link): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($link['created_at'])); ?></td>
                    <td class="text-left" style="font-weight:bold; color:#0056b3;"><?= esc($link['username']); ?></td>
                    <td class="text-left"><?= esc($link['title']); ?></td>
                    <td class="text-left">
                        <a href="<?= base_url($link['slug']); ?>"><?= base_url($link['slug']); ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Tidak ada link berita bulan ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

