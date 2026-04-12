<style>
    /* Luxury Dashboard CSS */
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        --secondary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --luxury-dark: #0f172a;
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    .luxury-container {
        padding: 10px;
        background: #f8fafc;
        border-radius: 20px;
    }

    .hero-stats-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 24px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2);
        margin-bottom: 30px;
        border: none;
    }

    .hero-stats-card::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(50px);
    }

    .hero-stats-card .score-big {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 10px;
        letter-spacing: -2px;
    }

    .luxury-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 25px;
        overflow: hidden;
    }

    .luxury-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }

    .luxury-card-header {
        padding: 20px 25px;
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .luxury-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .luxury-card-body {
        padding: 25px;
    }

    .status-pill {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .bg-luxury-gold { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; }
    .bg-luxury-blue { background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%); color: white; }
    .bg-luxury-purple { background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); color: white; }

    .table-luxury {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-luxury th {
        background: transparent;
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 20px;
    }

    .table-luxury tr {
        background: white;
        transition: all 0.2s;
    }

    .table-luxury td {
        padding: 15px 20px;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-luxury td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .table-luxury td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

    .table-luxury tr:hover td {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .icon-box-luxury {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }

    .dummy-btn {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .dummy-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="luxury-container">
    <div class="row">
        <div class="col-md-7">
            <div class="hero-stats-card">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <p class="text-uppercase fw-bold mb-2" style="opacity: 0.8; letter-spacing: 1px;">Indeks Kepuasan Global</p>
                        <h1 class="score-big"><?= $overallAvg; ?> <span style="font-size: 1.5rem; opacity: 0.7;">/ 4.00</span></h1>
                        <p class="mb-4" style="opacity: 0.9;">Berdasarkan rekapitulasi real-time seluruh layanan publik.</p>
                        <div class="d-flex align-items-center">
                            <span class="status-pill bg-white text-primary me-3">Sangat Baik</span>
                            <span style="font-size: 0.9rem;"><i class="fa fa-clock-o"></i> Update: Terakhir <?= date('H:i'); ?> WIB</span>
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block text-end">
                        <i class="fa fa-line-chart" style="font-size: 120px; opacity: 0.1;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="luxury-card h-100" style="background: var(--luxury-dark); color: white; border: none;">
                <div class="luxury-card-body d-flex flex-column justify-content-center h-100">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box-luxury bg-luxury-purple">
                            <i class="fa fa-magic"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Simulation Mode</h4>
                            <p class="mb-0 text-muted small">Kelola data dashboard statistik</p>
                        </div>
                    </div>
                    <p class="mb-4 text-muted">Gunakan fitur ini untuk mengisi dashboard dengan data simulasi (1.000 responden) guna keperluan presentasi atau pengujian visual.</p>
                    <a href="<?= adminUrl('skm/seed?confirm=yes'); ?>" class="dummy-btn" onclick="return confirm('Apakah Anda yakin ingin mengisi 1000 data dummy? Data lama akan dihapus.')">
                        Generate 1000 Data <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-bar-chart text-primary me-2"></i> Performa per Layanan</h3>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border" type="button">Filter <i class="fa fa-filter"></i></button>
                    </div>
                </div>
                <div class="luxury-card-body">
                    <div style="height: 350px;">
                        <canvas id="skmServiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-pie-chart text-danger me-2"></i> Distribusi Responden</h3>
                </div>
                <div class="luxury-card-body">
                    <div style="height: 350px;">
                        <canvas id="skmDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="luxury-card">
        <div class="luxury-card-header">
            <h3 class="luxury-card-title"><i class="fa fa-table text-success me-2"></i> Rekapitulasi Detail</h3>
        </div>
        <div class="luxury-card-body">
            <div class="table-responsive">
                <table class="table-luxury">
                    <thead>
                        <tr>
                            <th>JENIS LAYANAN</th>
                            <th>RESPONDEN</th>
                            <th>SKOR RATA-RATA</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats)):
                            foreach ($stats as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-luxury" style="width: 35px; height: 35px; font-size: 1rem; background: #f1f5f9; color: #475569;">
                                            <i class="fa fa-file-text-o"></i>
                                        </div>
                                        <span class="fw-bold"><?= esc($item->service_type); ?></span>
                                    </div>
                                </td>
                                <td><span class="fw-bold"><?= esc($item->total); ?></span> <span class="text-muted small">Orang</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-2"><?= number_format($item->avg_score, 2); ?></span>
                                        <div class="progress w-100" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                            <?php 
                                                $pct = ($item->avg_score / 4) * 100;
                                                $barClass = $item->avg_score >= 3.25 ? 'bg-success' : ($item->avg_score >= 2.5 ? 'bg-info' : 'bg-warning');
                                            ?>
                                            <div class="progress-bar <?= $barClass; ?>" style="width: <?= $pct; ?>%; border-radius: 10px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $score = $item->avg_score;
                                        if ($score >= 3.25) echo '<span class="status-pill bg-success text-white">Sangat Baik</span>';
                                        elseif ($score >= 2.5) echo '<span class="status-pill bg-info text-white">Baik</span>';
                                        elseif ($score >= 1.75) echo '<span class="status-pill bg-warning text-dark">Cukup</span>';
                                        else echo '<span class="status-pill bg-danger text-white">Buruk</span>';
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceLabels = [<?php foreach($stats as $s) echo '"' . $s->service_type . '",'; ?>];
        const avgScores = [<?php foreach($stats as $s) echo $s->avg_score . ','; ?>];
        const respondentCounts = [<?php foreach($stats as $s) echo $s->total . ','; ?>];

        // Service Performance Bar Chart
        const ctxBar = document.getElementById('skmServiceChart').getContext('2d');
        const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, 'rgba(37, 99, 235, 1)');
        gradientBar.addColorStop(1, 'rgba(37, 99, 235, 0.1)');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: serviceLabels,
                datasets: [{
                    label: 'Skor Rata-rata',
                    data: avgScores,
                    backgroundColor: gradientBar,
                    borderColor: '#2563eb',
                    borderWidth: 0,
                    borderRadius: 10,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 4,
                        grid: { borderDash: [5, 5], color: '#e2e8f0' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Distribution Doughnut Chart
        const ctxPie = document.getElementById('skmDistributionChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: serviceLabels,
                datasets: [{
                    data: respondentCounts,
                    backgroundColor: [
                        '#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                    ],
                    borderWidth: 8,
                    borderColor: '#ffffff',
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    });
</script>


