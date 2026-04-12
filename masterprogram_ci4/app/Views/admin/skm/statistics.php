<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-card bg-aqua">
            <div class="inner">
                <h3><?= $overallAvg; ?>/4.00</h3>
                <p>Indeks Kepuasan Global</p>
            </div>
            <div class="icon">
                <i class="fa fa-line-chart"></i>
            </div>
            <a href="#" class="small-card-footer">Statistik Real-time <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-card bg-purple">
            <div class="inner">
                <h3>Dummy</h3>
                <p>Populasi Data SKM</p>
            </div>
            <div class="icon">
                <i class="fa fa-database"></i>
            </div>
            <a href="<?= adminUrl('skm/seed?confirm=yes'); ?>" class="small-card-footer" onclick="return confirm('Apakah Anda yakin ingin mengisi 1000 data dummy? Data lama akan dihapus.')">Klik untuk Generate 1000 Data <i class="fa fa-magic"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title">Performa per Layanan</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="skmServiceChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Distribusi Responden</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="skmDistributionChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header with-border">
        <h3 class="card-title">Data Rekapitulasi per Jenis Layanan</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jenis Layanan</th>
                    <th>Jumlah Responden</th>
                    <th>Skor Rata-rata</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stats)):
                    foreach ($stats as $item): ?>
                    <tr>
                        <td><strong><?= esc($item->service_type); ?></strong></td>
                        <td><?= esc($item->total); ?> Orang</td>
                        <td><?= number_format($item->avg_score, 2); ?> / 4.00</td>
                        <td>
                            <?php 
                                $score = $item->avg_score;
                                if ($score >= 3.25) echo '<span class="label badge bg-success">SANGAT BAIK</span>';
                                elseif ($score >= 2.5) echo '<span class="label badge bg-info">BAIK</span>';
                                elseif ($score >= 1.75) echo '<span class="label badge bg-warning">KURANG BAIK</span>';
                                else echo '<span class="label badge bg-danger">TIDAK BAIK</span>';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data from PHP
        const serviceLabels = [<?php foreach($stats as $s) echo '"' . $s->service_type . '",'; ?>];
        const avgScores = [<?php foreach($stats as $s) echo $s->avg_score . ','; ?>];
        const respondentCounts = [<?php foreach($stats as $s) echo $s->total . ','; ?>];

        // Service Performance Bar Chart
        const ctxBar = document.getElementById('skmServiceChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: serviceLabels,
                datasets: [{
                    label: 'Skor Rata-rata',
                    data: avgScores,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4
                    }
                }
            }
        });

        // Distribution Pie Chart
        const ctxPie = document.getElementById('skmDistributionChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: serviceLabels,
                datasets: [{
                    data: respondentCounts,
                    backgroundColor: [
                        '#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40'
                    ]
                }]
            }
        });
    });
</script>


