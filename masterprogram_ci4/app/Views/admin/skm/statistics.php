<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Ultra-Premium Luxury Dashboard CSS */
    :root {
        --midnight-deep: #050b18;
        --midnight-main: #0a192f;
        --midnight-card: rgba(17, 34, 64, 0.7);
        --royal-gold: #d4af37;
        --royal-gold-light: #f1c40f;
        --royal-gold-glow: rgba(212, 175, 55, 0.3);
        --glass-border: rgba(212, 175, 55, 0.2);
        --text-platinum: #e6f1ff;
        --text-gold: #f6e05e;
    }

    .luxury-container {
        padding: 25px;
        background: radial-gradient(circle at 10% 20%, var(--midnight-main) 0%, var(--midnight-deep) 100%);
        border-radius: 30px;
        color: var(--text-platinum);
        font-family: 'Inter', sans-serif;
        box-shadow: inset 0 0 100px rgba(0,0,0,0.5);
        min-height: 100vh;
    }

    h1, h2, h3, h4, h5, .score-big {
        font-family: 'Outfit', sans-serif;
    }

    .hero-stats-card {
        background: linear-gradient(135deg, #112240 0%, #1a365d 100%);
        color: white;
        border-radius: 32px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 
                    inset 0 0 20px rgba(212, 175, 55, 0.05);
        margin-bottom: 30px;
        border: 1px solid var(--glass-border);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hero-stats-card::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, var(--royal-gold-glow) 0%, transparent 70%);
        filter: blur(60px);
        opacity: 0.5;
        pointer-events: none;
    }

    .hero-stats-card:hover {
        transform: translateY(-5px) scale(1.01);
        border-color: var(--royal-gold);
        box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.6), 
                    0 0 30px var(--royal-gold-glow);
    }

    .score-big {
        font-size: 5.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 15px;
        letter-spacing: -3px;
        background: linear-gradient(to bottom, #ffffff 30%, var(--royal-gold) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));
        animation: pulseGold 3s infinite alternate;
    }

    @keyframes pulseGold {
        0% { filter: drop-shadow(0 0 5px rgba(255,255,255,0.2)); }
        100% { filter: drop-shadow(0 0 20px var(--royal-gold-glow)); }
    }

    .luxury-card {
        background: var(--midnight-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        transition: all 0.4s ease;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .luxury-card:hover {
        background: rgba(17, 34, 64, 0.9);
        border-color: rgba(212, 175, 55, 0.4);
        transform: translateY(-8px);
    }

    .luxury-card-header {
        padding: 25px 30px;
        background: rgba(255,255,255,0.03);
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .luxury-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-gold);
        margin: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .luxury-card-body {
        padding: 30px;
    }

    .status-pill {
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid transparent;
    }

    .bg-luxury-gold { 
        background: linear-gradient(135deg, #d4af37 0%, #f1c40f 100%); 
        color: #000; 
        box-shadow: 0 0 15px var(--royal-gold-glow);
    }

    .table-luxury {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .table-luxury th {
        background: transparent;
        border: none;
        color: #8892b0;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 5px 25px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .table-luxury tr {
        background: rgba(255,255,255,0.02);
        transition: all 0.3s;
    }

    .table-luxury td {
        padding: 20px 25px;
        border-top: 1px solid var(--glass-border);
        border-bottom: 1px solid var(--glass-border);
        vertical-align: middle;
        color: var(--text-platinum);
    }

    .table-luxury td:first-child { border-left: 1px solid var(--glass-border); border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .table-luxury td:last-child { border-right: 1px solid var(--glass-border); border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

    .table-luxury tr:hover td {
        background: rgba(212, 175, 55, 0.05);
        border-color: var(--royal-gold);
    }

    .icon-box-luxury {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--royal-gold);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .dummy-btn {
        background: linear-gradient(135deg, var(--royal-gold) 0%, #b8860b 100%);
        color: #000 !important;
        padding: 14px 28px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        transition: all 0.4s;
        border: none;
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
    }

    .dummy-btn:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
        filter: brightness(1.1);
    }

    /* Animation */
    .animate-up {
        animation: fadeInUp 0.8s both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>


<?php
function getStars($score) {
    if (!$score) return '';
    $stars = ($score / 4) * 5;
    $output = '<div class="star-rating d-inline-block" style="color: var(--royal-gold); font-size: 1.2rem; filter: drop-shadow(0 0 5px var(--royal-gold-glow));">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($stars)) {
            $output .= '<i class="fa fa-star me-1"></i>';
        } elseif ($i - 0.5 <= $stars) {
            $output .= '<i class="fa fa-star-half-o me-1"></i>';
        } else {
            $output .= '<i class="fa fa-star-o me-1"></i>';
        }
    }
    $output .= '</div>';
    return $output;
}
$monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
?>

<div class="luxury-container">
    <div class="row">
        <div class="col-md-7 animate-up" style="animation-delay: 0.1s;">
            <div class="hero-stats-card">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <p class="text-uppercase fw-bold mb-2" style="color: var(--text-gold); opacity: 0.9; letter-spacing: 2px; font-size: 0.8rem;">Indeks Kepuasan Global</p>
                        <div class="d-flex align-items-end mb-2">
                            <h1 class="score-big mb-0 me-3"><?= $overallAvg; ?></h1>
                            <div class="mb-2">
                                <?= getStars($overallAvg); ?>
                            </div>
                        </div>
                        <p class="mb-4" style="color: var(--text-platinum); opacity: 0.8; font-size: 1.1rem;">Berdasarkan rekapitulasi real-time layanan <b>SPKT, SIM dan SKCK</b>.</p>
                        <div class="d-flex align-items-center">
                            <span class="status-pill bg-luxury-gold me-3">Sangat Memuaskan</span>
                            <span style="font-size: 0.85rem; color: var(--text-platinum); opacity: 0.6;"><i class="fa fa-clock-o"></i> Update: <?= date('d M Y, H:i'); ?> WIB</span>
                        </div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block text-end">
                        <div style="position: relative; display: inline-block;">
                            <i class="fa fa-line-chart" style="font-size: 140px; color: var(--royal-gold); opacity: 0.15;"></i>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <i class="fa fa-star" style="font-size: 60px; color: var(--royal-gold); opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 animate-up" style="animation-delay: 0.2s;">
            <div class="luxury-card h-100" style="border: 1px solid rgba(212, 175, 55, 0.3);">
                <div class="luxury-card-body d-flex flex-column justify-content-center h-100">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box-luxury">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0 fw-bold" style="color: var(--text-gold);">System Control</h4>
                            <p class="mb-0 text-muted small">Intelligence Seeding Engine</p>
                        </div>
                    </div>
                    <p class="mb-4" style="color: var(--text-platinum); opacity: 0.7; line-height: 1.6;">Gunakan fitur <b>Intelligence Seeding</b> untuk mensimulasikan data 1.000 responden pada layanan SPKT, SIM, dan SKCK.</p>
                    <a href="<?= adminUrl('skm/seed?confirm=yes'); ?>" class="dummy-btn" onclick="return confirm('Apakah Anda yakin ingin mengisi 1000 data dummy? Data lama akan dihapus.')">
                        Initialize Simulation <i class="fa fa-bolt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 animate-up" style="animation-delay: 0.3s;">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-bar-chart me-2"></i> Performa Layanan Terpadu</h3>
                </div>
                <div class="luxury-card-body">
                    <div style="height: 380px; position: relative;">
                        <canvas id="skmServiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 animate-up" style="animation-delay: 0.4s;">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-pie-chart me-2"></i> Demografi Responden</h3>
                </div>
                <div class="luxury-card-body">
                    <div style="height: 380px; position: relative;">
                        <canvas id="skmDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 animate-up" style="animation-delay: 0.5s;">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-calendar me-2"></i> Penilaian per Tahun</h3>
                </div>
                <div class="luxury-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-luxury">
                            <thead>
                                <tr>
                                    <th>TAHUN</th>
                                    <th>VOL</th>
                                    <th>INDEKS SKOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($yearlyStats)): foreach ($yearlyStats as $y): ?>
                                    <tr>
                                        <td><span class="fw-bold" style="color: var(--text-gold);"><?= $y->year; ?></span></td>
                                        <td><?= $y->total; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold me-2"><?= number_format($y->avg_score, 2); ?></span>
                                                <?= getStars($y->avg_score); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 animate-up" style="animation-delay: 0.6s;">
            <div class="luxury-card">
                <div class="luxury-card-header">
                    <h3 class="luxury-card-title"><i class="fa fa-calendar-check-o me-2"></i> Penilaian per Bulan (<?= date('Y'); ?>)</h3>
                </div>
                <div class="luxury-card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table-luxury">
                            <thead>
                                <tr>
                                    <th>BULAN</th>
                                    <th>VOL</th>
                                    <th>INDEKS SKOR</th>
                                </tr>
                            </thead>
                                <tbody>
                                    <?php 
                                    $monthlyDataMap = [];
                                    if (!empty($monthlyStats)) {
                                        foreach ($monthlyStats as $ms) $monthlyDataMap[$ms->month] = $ms;
                                    }
                                    for ($i = 1; $i <= 12; $i++): 
                                        $m = isset($monthlyDataMap[$i]) ? $monthlyDataMap[$i] : (object)['month' => $i, 'total' => 0, 'avg_score' => 0];
                                    ?>
                                        <tr>
                                            <td><span class="fw-bold" style="color: var(--text-platinum);"><?= $monthNames[$i - 1]; ?></span></td>
                                            <td><?= $m->total; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold me-2"><?= $m->avg_score > 0 ? number_format($m->avg_score, 2) : '-'; ?></span>
                                                    <?= getStars($m->avg_score); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="luxury-card animate-up" style="animation-delay: 0.7s;">
        <div class="luxury-card-header">
            <h3 class="luxury-card-title"><i class="fa fa-list-ul me-2"></i> Rekapitulasi Analitik per Layanan</h3>
        </div>
        <div class="luxury-card-body p-0">
            <div class="table-responsive">
                <table class="table-luxury">
                    <thead>
                        <tr>
                            <th style="width: 35%;">LAYANAN PUBLIK</th>
                            <th>VOLUME</th>
                            <th>INDEKS & BINTANG</th>
                            <th class="text-center">PREDIKAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats)):
                            foreach ($stats as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-luxury me-3" style="width: 42px; height: 42px; font-size: 1.1rem; background: rgba(255,255,255,0.03);">
                                            <i class="fa fa-shield"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block" style="color: var(--text-platinum);"><?= esc($item->service_type); ?></span>
                                            <span class="text-muted tiny" style="font-size: 0.7rem; text-transform: uppercase;">Layanan Utama</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: var(--text-platinum);"><?= esc($item->total); ?></span> 
                                    <small class="text-muted">Resp.</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold me-2" style="font-size: 1.1rem; color: var(--royal-gold);"><?= number_format($item->avg_score, 2); ?></span>
                                            <?= getStars($item->avg_score); ?>
                                        </div>
                                        <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255,255,255,0.05);">
                                            <?php $pct = ($item->avg_score / 4) * 100; ?>
                                            <div class="progress-bar" style="width: <?= $pct; ?>%; background: var(--royal-gold); box-shadow: 0 0 10px var(--royal-gold-glow);"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $score = $item->avg_score;
                                        if ($score >= 3.25) echo '<span class="status-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2);">Sangat Baik</span>';
                                        elseif ($score >= 2.5) echo '<span class="status-pill" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.2);">Baik</span>';
                                        else echo '<span class="status-pill" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2);">Cukup</span>';
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

<div id="luxury-particles" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.3;"></div>

<script>
    // Particle Effect
    function createParticles() {
        const container = document.getElementById('luxury-particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = Math.random() * 3 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = Math.random() > 0.5 ? '#d4af37' : '#ffffff';
            particle.style.borderRadius = '50%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.opacity = Math.random() * 0.5;
            particle.style.animation = `float ${Math.random() * 10 + 10}s linear infinite`;
            container.appendChild(particle);
        }
    }

    const styleSheet = document.createElement("style");
    styleSheet.innerText = `
        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-100vh) translateX(${Math.random() * 50 - 25}px); opacity: 0; }
        }
    `;
    document.head.appendChild(styleSheet);
    createParticles();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data from PHP
        const serviceLabels = [<?php foreach($stats as $s) echo '"' . truncateString($s->service_type, 15) . '",'; ?>];
        const fullLabels = [<?php foreach($stats as $s) echo '"' . $s->service_type . '",'; ?>];
        const avgScores = [<?php foreach($stats as $s) echo $s->avg_score . ','; ?>];
        const respondentCounts = [<?php foreach($stats as $s) echo $s->total . ','; ?>];

        // Global Chart Defaults
        Chart.defaults.color = '#8892b0';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // Service Performance Bar Chart
        const ctxBar = document.getElementById('skmServiceChart').getContext('2d');
        
        // Create Gradients
        const goldGradient = ctxBar.createLinearGradient(0, 0, 0, 400);
        goldGradient.addColorStop(0, 'rgba(212, 175, 55, 1)');
        goldGradient.addColorStop(1, 'rgba(212, 175, 55, 0.1)');

        const blueGradient = ctxBar.createLinearGradient(0, 0, 0, 400);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 1)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: serviceLabels,
                datasets: [{
                    label: 'Skor Rata-rata',
                    data: avgScores,
                    backgroundColor: goldGradient,
                    borderColor: '#d4af37',
                    borderWidth: 1,
                    borderRadius: 12,
                    barThickness: 35,
                    shadowBlur: 15,
                    shadowColor: 'rgba(212, 175, 55, 0.4)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#112240',
                        titleColor: '#f6e05e',
                        bodyColor: '#e6f1ff',
                        borderColor: 'rgba(212, 175, 55, 0.3)',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                        callbacks: {
                            title: function(context) { return fullLabels[context[0].dataIndex]; }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 4,
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { stepSize: 1, color: '#8892b0' }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#8892b0' }
                    }
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
                        '#d4af37', '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 25,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 25,
                            color: '#8892b0',
                            font: { size: 11 }
                        } 
                    },
                    tooltip: {
                        backgroundColor: '#112240',
                        padding: 15,
                        displayColors: true,
                        boxPadding: 8
                    }
                }
            }
        });
    });
</script>

<?php
function truncateString($str, $len) {
    if (strlen($str) > $len) {
        return substr($str, 0, $len) . '...';
    }
    return $str;
}
?>



