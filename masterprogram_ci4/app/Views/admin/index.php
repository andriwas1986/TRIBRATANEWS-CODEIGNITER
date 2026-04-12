<?php
    // --- [1] LOGIC PHP: AUTO-FETCH & JOIN TABLE ---
    $db = \Config\Database::connect();
    
    // JOIN Tabel Images
    $latestPosts = $db->table('posts')
        ->select('posts.*, categories.name as category_name, 
                  images.image_mid, images.image_small, images.image_default, images.file_name as img_filename')
        ->join('categories', 'categories.id = posts.category_id', 'left')
        ->join('images', 'images.id = posts.image_id', 'left')
        ->orderBy('posts.created_at', 'DESC')
        ->limit(5)
        ->get()->getResult();

    /**
     * FUNGSI LINK GAMBAR (UNIVERSAL & DINAMIS FIX)
     */
    if (!function_exists('getRealImage')) {
        function getRealImage($post) {
            $path = "";
            if (!empty($post->image_mid)) $path = $post->image_mid;
            elseif (!empty($post->image_small)) $path = $post->image_small;
            elseif (!empty($post->image_default)) $path = $post->image_default;
            elseif (!empty($post->img_filename)) $path = $post->img_filename;

            if (empty($path)) {
                return "https://via.placeholder.com/100/f1f5f9/94a3b8?text=No+Img";
            }

            if (strpos($path, 'http') === 0) {
                return $path;
            }

            if (strpos($path, 'uploads') === false) {
                $folderDate = date('Ym', strtotime($post->created_at));
                $path = 'uploads/images/' . $folderDate . '/' . $path;
            }

            $cleanPath = ltrim($path, '/');
            return base_url($cleanPath);
        }
    }
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&family=Share+Tech+Mono&display=swap" rel="stylesheet">

<style>
    /* --- TEMA ROYAL LUXURY (HOLOGRAPHIC BLUE) --- */
    :root {
        --bg-body: #f0f7ff;
        --radius: 20px;
        --font-main: 'Outfit', sans-serif;
        --font-digital: 'Share Tech Mono', monospace; /* Font Digital Keren */
        --electric-blue: #00a8ff; 
        --deep-blue: #1e3799;
    }

    .content-wrapper { background: var(--bg-body) !important; font-family: var(--font-main); }
    .dashboard-ultimate { padding: 25px; }

    /* TICKER */
    .ticker-wrapper {
        background: linear-gradient(90deg, var(--deep-blue) 0%, var(--electric-blue) 100%);
        color: white; border-radius: 50px; padding: 8px 20px; border: none;
        margin-bottom: 25px; display: flex; align-items: center;
        card-shadow: 0 10px 25px rgba(0, 168, 255, 0.3); height: 45px;
    }
    .ticker-label {
        background: rgba(255,255,255,0.2);
        color: #fff; padding: 3px 12px; border-radius: 20px; font-size: 10px; font-weight: 800;
        margin-right: 15px; white-space: nowrap;
        display: flex; align-items: center; gap: 5px;
    }
    .dot-live { width: 6px; height: 6px; background: #fff; border-radius: 50%; animation: blink 1s infinite; card-shadow: 0 0 10px #fff; }
    @keyframes blink { 50% { opacity: 0.4; } }
    .ticker-text { width: 100%; font-size: 12px; font-weight: 500; letter-spacing: 0.5px; opacity: 1; padding-top: 2px; }

    /* STAT CARDS */
    .card-colorful {
        border-radius: var(--radius); padding: 22px; margin-bottom: 25px;
        color: white; position: relative; overflow: hidden;
        card-shadow: 0 15px 30px rgba(0,0,0,0.08); transition: all 0.3s ease;
    }
    .card-colorful:hover { transform: translateY(-5px); card-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    .grad-blue { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
    .grad-orange { background: linear-gradient(135deg, #f97316 0%, #ef4444 100%); }
    .grad-purple { background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%); }
    .grad-green { background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); }
    .cc-info h3 { font-size: 32px; font-weight: 800; margin: 0; }
    .cc-info p { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; margin:0; }
    .cc-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 35px; opacity: 0.2; }

    /* MAP CONTAINER */
    .map-container {
        background: linear-gradient(135deg, #ffffff 0%, #e6f7ff 100%);
        border-radius: var(--radius); margin-bottom: 30px; position: relative; overflow: hidden;
        card-shadow: 0 20px 60px rgba(0, 168, 255, 0.15), inset 0 0 30px rgba(255,255,255,0.8);
        height: 480px; border: 1px solid #bae6ff;
    }
    .map-bg-img {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 85%; pointer-events: none; z-index: 1;
        filter: sepia(1) hue-rotate(190deg) saturate(500%) opacity(0.3);
    }
    #spiderCanvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; mix-blend-mode: screen; }
    .flying-dot {
        position: absolute; width: 6px; height: 6px; background: var(--electric-blue); border-radius: 50%;
        card-shadow: 0 0 10px var(--electric-blue), 0 0 20px rgba(0, 168, 255, 0.6);
        z-index: 3; pointer-events: none;
    }
    .map-overlay {
        position: relative; z-index: 10; padding: 25px; height: 100%;
        display: flex; flex-direction: column; justify-content: space-between; pointer-events: none;
    }
    .live-counter-card {
        background: rgba(255, 255, 255, 0.7); padding: 15px 25px; border-radius: 16px; 
        border: 1px solid rgba(0, 168, 255, 0.3); backdrop-filter: blur(15px); width: fit-content;
        card-shadow: 0 10px 30px rgba(0, 168, 255, 0.15);
    }
    .counter-val { 
        font-size: 36px; font-weight: 800; color: var(--electric-blue); 
        letter-spacing: -1px; font-family: monospace; text-shadow: 0 0 15px rgba(0, 168, 255, 0.4);
    }

    /* NEWS LIST */
    .glass-panel {
        background: #ffffff; border-radius: var(--radius); padding: 25px;
        card-shadow: 0 15px 40px rgba(0, 168, 255, 0.05); margin-bottom: 30px; 
        border: 1px solid #bae6ff; height: 480px; display: flex; flex-direction: column;
    }
    .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-head h4 { margin: 0; font-weight: 800; color: var(--deep-blue); font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; }

    .news-list { list-style: none; padding: 0; margin: 0; flex-grow: 1; overflow-y: auto; }
    .news-item {
        display: flex; align-items: center; padding: 14px 0;
        border-bottom: 1px solid #f0f7ff; transition: all 0.3s ease; cursor: pointer;
    }
    .news-item:hover { transform: translateX(8px); background: #f8fdff; }
    .news-thumb {
        width: 60px; height: 60px; border-radius: 14px; object-fit: cover; margin-right: 15px;
        card-shadow: 0 4px 15px rgba(0, 168, 255, 0.1); border: 2px solid #fff; background: #f0f7ff;
    }
    .news-title { font-size: 13px; font-weight: 700; color: #334155; margin: 0 0 5px 0; line-height: 1.4; }
    .news-meta { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 8px; }
    .cat-badge { background: #e6f7ff; color: var(--electric-blue); padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 9px; text-transform: uppercase; }

    /* --- TRAFFIC CHART DIGITAL STYLE (KECIL & MENYALA) --- */
    .digital-panel {
        background: #0f172a; /* Background Gelap biar Angka Digital Menyala */
        border-radius: var(--radius); padding: 20px;
        card-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); margin-bottom: 30px; 
        border: 1px solid #1e293b;
        display: flex; align-items: center; justify-content: space-between;
        position: relative; overflow: hidden;
    }
    .digital-panel::after {
        content:''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--electric-blue), transparent);
        animation: scanline 3s infinite linear;
    }
    @keyframes scanline { 0% {transform: translateX(-100%);} 100% {transform: translateX(100%);} }

    .digital-metrics { display: flex; gap: 30px; width: 40%; }
    .digi-item { position: relative; }
    .digi-item h5 { margin: 0; font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    
    /* Font Digital Bergerak */
    .digi-val { 
        margin: 0; font-size: 24px; font-weight: 400; color: var(--electric-blue); 
        font-family: var(--font-digital);
        text-shadow: 0 0 10px rgba(0, 168, 255, 0.8);
    }
    
    .chart-compact-wrapper { width: 60%; height: 80px; position: relative; }

    /* TABLE */
    .tbl-modern td { padding: 15px 0; border-bottom: 1px solid #f0f7ff; vertical-align: middle; font-size: 13px; }
    .user-avt { width: 45px; height: 45px; border-radius: 12px; object-fit: cover; card-shadow: 0 4px 15px rgba(0, 168, 255, 0.1); }
</style>

<div class="dashboard-ultimate">

    <div class="ticker-wrapper">
        <div class="ticker-label"><span class="dot-live"></span> SYSTEM OK</div>
        <marquee class="ticker-text" scrollamount="5">
            <b>MONITORING:</b> Semua sistem berjalan normal. &nbsp;&nbsp; | &nbsp;&nbsp; 
            Artikel: <b><?= $postsCount ?? 0; ?></b> &nbsp;&nbsp; | &nbsp;&nbsp; 
            Pending: <b><?= $pendingPostsCount ?? 0; ?></b> &nbsp;&nbsp; | &nbsp;&nbsp; 
            Real-time Traffic: ACTIVE.
        </marquee>
    </div>

    <?php if (hasPermission('admin_panel')): ?>
    <div class="row">
        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?= adminURL('posts'); ?>" style="text-decoration:none;">
                <div class="card-colorful grad-blue">
                    <div class="cc-info"><p>Total Post</p><h3><?= $postsCount ?? 0; ?></h3></div>
                    <i class="fa fa-newspaper-o cc-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?= adminURL('pending-posts'); ?>" style="text-decoration:none;">
                <div class="card-colorful grad-orange">
                    <div class="cc-info"><p>Pending</p><h3><?= $pendingPostsCount ?? 0; ?></h3></div>
                    <i class="fa fa-hourglass-2 cc-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?= adminURL('drafts'); ?>" style="text-decoration:none;">
                <div class="card-colorful grad-purple">
                    <div class="cc-info"><p>Drafts</p><h3><?= $draftsCount ?? 0; ?></h3></div>
                    <i class="fa fa-file-text-o cc-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <a href="<?= adminURL('scheduled-posts'); ?>" style="text-decoration:none;">
                <div class="card-colorful grad-green">
                    <div class="cc-info"><p>Terjadwal</p><h3><?= $scheduledPostsCount ?? 0; ?></h3></div>
                    <i class="fa fa-clock-o cc-icon"></i>
                </div>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="map-container">
                <canvas id="spiderCanvas"></canvas>
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/80/World_map_-_low_resolution.svg" class="map-bg-img">
                <div class="map-overlay">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h4 style="margin:0; font-weight:800; font-size:18px; color:var(--deep-blue);">LIVE VISITOR MAP</h4>
                            <small style="color:#64748b; font-weight:600;">Holographic Data Nodes</small>
                        </div>
                        <div style="background:rgba(0, 168, 255, 0.1); color:var(--electric-blue); padding:5px 15px; border-radius:30px; font-size:10px; font-weight:800; border:1px solid rgba(0, 168, 255, 0.3);">ONLINE</div>
                    </div>
                    <div class="live-counter-card">
                        <div class="counter-val" id="liveVisitorCount">1,245</div>
                        <div style="font-size:10px; text-transform:uppercase; letter-spacing:1.5px; color:#64748b; font-weight:700; margin-top:5px;">Active Nodes</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="glass-panel">
                <div class="panel-head">
                    <h4><i class="fa fa-flash" style="color:var(--electric-blue)"></i> Recent Posts</h4>
                    <a href="<?= adminURL('posts'); ?>" style="font-size:10px; font-weight:800; color:var(--electric-blue); text-decoration:none;">MORE</a>
                </div>
                
                <ul class="news-list">
                    <?php if (!empty($latestPosts)): foreach ($latestPosts as $post): ?>
                        <li class="news-item" onclick="window.open('<?= base_url($post->slug); ?>', '_blank')" title="Klik untuk baca">
                            <img src="<?= getRealImage($post); ?>" class="news-thumb" alt="news" 
                                 onerror="this.src='https://via.placeholder.com/80/f1f5f9/94a3b8?text=Error';">
                            <div class="news-content">
                                <h5 class="news-title"><?= esc(characterLimiter($post->title, 55, '...')); ?></h5>
                                <div class="news-meta">
                                    <span class="cat-badge"><?= esc($post->category_name ?? 'Article'); ?></span>
                                    <span><?= timeAgo($post->created_at); ?></span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; else: ?>
                        <li class="news-item"><div style="padding:20px; text-align:center; color:#94a3b8;">Belum ada berita.</div></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="digital-panel">
                <div class="digital-metrics">
                    <div class="digi-item">
                        <h5>SESSIONS</h5>
                        <h2 class="digi-val" id="digiSession">24592</h2>
                    </div>
                    <div class="digi-item">
                        <h5>VIEWS</h5>
                        <h2 class="digi-val" id="digiView">86400</h2>
                    </div>
                    <div class="digi-item">
                        <h5>BOUNCE</h5>
                        <h2 class="digi-val" id="digiBounce">42.3%</h2>
                    </div>
                </div>

                <div class="chart-compact-wrapper">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (hasPermission('users')): ?>
        <div class="col-12 col-lg-6">
            <div class="glass-panel" style="height:auto; padding-bottom:10px;">
                <div class="panel-head"><h4>New Users</h4><a href="<?= adminURL('users'); ?>" class="btn btn-xs btn-default" style="border-radius:20px; border:1px solid #bae6ff; color:var(--deep-blue);">View All</a></div>
                <table class="tbl-modern" style="width:100%;">
                    <?php if (!empty($latestUsers)): foreach ($latestUsers as $user) : ?>
                    <tr style="border-bottom: 1px solid #f0f7ff;">
                        <td width="50" style="padding:12px 0;"><img src="<?= getUserAvatar($user->avatar); ?>" class="user-avt"></td>
                        <td><b style="color:#334155;"><?= esc($user->username); ?></b><br><small style="color:#94a3b8;"><?= timeAgo($user->created_at); ?></small></td>
                        <td align="right"><span style="background:#e6f7ff; color:var(--electric-blue); padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; border:1px solid #bae6ff;">ACTIVE</span></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (hasPermission('comments_contact')): ?>
        <div class="col-12 col-lg-6">
            <div class="glass-panel" style="height:auto; padding-bottom:10px;">
                <div class="panel-head"><h4>Latest Inbox</h4><a href="<?= adminURL('contact-messages'); ?>" class="btn btn-xs btn-default" style="border-radius:20px; border:1px solid #bae6ff; color:var(--deep-blue);">View All</a></div>
                <table class="tbl-modern" style="width:100%;">
                    <?php if (!empty($latestContactMessages)): foreach ($latestContactMessages as $msg): ?>
                    <tr style="border-bottom: 1px solid #f0f7ff;">
                        <td style="padding:12px 0;"><b style="color:#334155;"><?= esc($msg->name); ?></b><br><span style="font-size:11px; color:#64748b;"><?= esc($msg->message); ?></span></td>
                        <td align="right" style="font-size:11px; color:#94a3b8; white-space:nowrap;"><?= formatDate($msg->created_at); ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td class="text-center text-muted" style="padding:20px;">Inbox empty.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. ANIMASI ANGKA DIGITAL (BERGERAK TERUS)
    const elSession = document.getElementById('digiSession');
    const elView = document.getElementById('digiView');
    const elBounce = document.getElementById('digiBounce');

    let sessionVal = 24592;
    let viewVal = 86400;
    
    setInterval(() => {
        // Random Naik Turun
        sessionVal += Math.floor(Math.random() * 15) - 5; 
        viewVal += Math.floor(Math.random() * 30) - 10;
        let bounceVal = (42 + Math.random()).toFixed(1);

        elSession.innerText = sessionVal.toLocaleString();
        elView.innerText = viewVal.toLocaleString();
        elBounce.innerText = bounceVal + '%';
        
        // Efek Flash Warna saat update
        elSession.style.color = '#fff';
        setTimeout(() => elSession.style.color = '#00a8ff', 100);
    }, 2000); // Update tiap 2 detik

    // 2. SPIDER WEB (BLUE ENERGY)
    const canvas = document.getElementById('spiderCanvas');
    const ctx = canvas.getContext('2d');
    const container = document.querySelector('.map-container');
    
    function resize() { canvas.width = container.offsetWidth; canvas.height = container.offsetHeight; }
    window.addEventListener('resize', resize); resize();

    const particles = [];
    for (let i = 0; i < 60; i++) {
        particles.push({
            x: Math.random() * canvas.width, y: Math.random() * canvas.height,
            vx: (Math.random() - 0.5) * 0.7, vy: (Math.random() - 0.5) * 0.7
        });
    }

    function animateMap() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        ctx.shadowBlur = 10;
        ctx.shadowColor = 'rgba(0, 168, 255, 0.8)';
        ctx.fillStyle = '#00a8ff'; 

        for (let i = 0; i < particles.length; i++) {
            let p = particles[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.vy *= -1;
            ctx.beginPath(); ctx.arc(p.x, p.y, 2.5, 0, Math.PI * 2); ctx.fill();

            for (let j = i + 1; j < particles.length; j++) {
                let p2 = particles[j];
                let dist = Math.sqrt(Math.pow(p.x - p2.x, 2) + Math.pow(p.y - p2.y, 2));
                if (dist < 140) {
                    ctx.strokeStyle = `rgba(0, 168, 255, ${1 - dist / 140})`;
                    ctx.lineWidth = 0.8;
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(p2.x, p2.y); ctx.stroke();
                }
            }
        }
        requestAnimationFrame(animateMap);
    }
    animateMap();

    function createFlyingDot() {
        const dot = document.createElement('div');
        dot.className = 'flying-dot';
        container.appendChild(dot);
        const startX = Math.random() * container.offsetWidth;
        const startY = Math.random() * container.offsetHeight;
        dot.style.left = startX + 'px'; dot.style.top = startY + 'px';
        dot.animate([
            { opacity:0, transform:'scale(0)' },
            { opacity:1, transform:'scale(2)', offset:0.1 }, 
            { opacity:0, transform:`translate(${Math.random()*250-125}px, ${Math.random()*250-125}px) scale(0)` }
        ], { duration:2500, easing:'ease-out' }).onfinish = () => dot.remove();
    }
    setInterval(createFlyingDot, 400);

    const counter = document.getElementById('liveVisitorCount');
    let val = 1245;
    setInterval(() => { val += Math.floor(Math.random() * 10) - 5; if(val<100)val=100; counter.innerText = val.toLocaleString(); }, 2000);

    // 3. CHART COMPACT (BLUE THEME)
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    const grad = ctxTraffic.createLinearGradient(0, 0, 0, 200);
    grad.addColorStop(0, 'rgba(0, 168, 255, 0.5)'); 
    grad.addColorStop(1, 'rgba(0, 168, 255, 0.0)'); 

    new Chart(ctxTraffic, {
        type: 'line',
        data: {
            labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
            datasets: [{
                label: 'Data',
                data: [40, 60, 45, 80, 70, 95, 100],
                borderColor: '#00a8ff',
                backgroundColor: grad,
                borderWidth: 2,
                pointRadius: 0,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { display:false, min:0 },
                x: { display:false }
            },
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            interaction: { intersect: false },
            elements: { point: { radius: 0 } }
        }
    });
});
</script>

