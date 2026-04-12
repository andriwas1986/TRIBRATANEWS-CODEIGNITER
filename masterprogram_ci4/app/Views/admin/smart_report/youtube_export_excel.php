<!DOCTYPE html>
<html>
<head>
    <title>Laporan YouTube</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        th { 
            background-color: #cc0000; /* Warna Merah YouTube */
            color: #ffffff; 
            text-align: center; 
            font-weight: bold; 
            height: 30px;
            vertical-align: middle;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .subtitle { font-size: 12px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="title">LAPORAN HARIAN & REKAPAN KONTEN YOUTUBE</div>
    <div class="subtitle"><?= $title_label ?? $date_indo; ?></div>
    <br>

    <table>
        <thead>
            <tr>
                <th width="50">NO</th>
                <th width="120">TANGGAL</th>
                <th width="300">SATKER (PENGINPUT)</th>
                <th width="100">KATEGORI</th>
                <th width="500">LINK YOUTUBE</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($links)): ?>
                <?php foreach($links as $row): ?>
                <tr>
                    <td class="center"><?= $row['no']; ?></td>
                    
                    <td class="center"><?= isset($row['tanggal']) ? $row['tanggal'] : date('d/m/Y', strtotime($row['created_at'])); ?></td>
                    
                    <td class="bold"><?= $row['satker']; ?></td>
                    
                    <td class="center"><?= $row['category']; ?></td>
                    
                    <td><a href="<?= $row['url']; ?>" target="_blank"><?= $row['url']; ?></a></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="center" style="padding: 20px;">Tidak ada data konten YouTube untuk periode/tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

