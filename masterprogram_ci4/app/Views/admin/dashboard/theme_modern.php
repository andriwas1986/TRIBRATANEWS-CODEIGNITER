<?php
    $db = \Config\Database::connect();
    $latestPosts = $db->table('posts')
        ->select('posts.*, categories.name as category_name, 
                  images.image_mid, images.image_small, images.image_default, images.file_name as img_filename')
        ->join('categories', 'categories.id = posts.category_id', 'left')
        ->join('images', 'images.id = posts.image_id', 'left')
        ->orderBy('posts.created_at', 'DESC')
        ->limit(6)
        ->get()->getResult();

    if (!function_exists('getRealImage')) {
        function getRealImage($post) {
            $path = "";
            if (!empty($post->image_mid)) $path = $post->image_mid;
            elseif (!empty($post->image_small)) $path = $post->image_small;
            elseif (!empty($post->image_default)) $path = $post->image_default;
            elseif (!empty($post->img_filename)) $path = $post->img_filename;
            if (empty($path)) return "https://via.placeholder.com/100/f1f5f9/94a3b8?text=No+Img";
            if (strpos($path, 'http') === 0) return $path;
            if (strpos($path, 'uploads') === false) {
                $folderDate = date('Ym', strtotime($post->created_at));
                $path = 'uploads/images/' . $folderDate . '/' . $path;
            }
            return base_url(ltrim($path, '/'));
        }
    }
?>

<style>
    .modern-dashboard { padding: 30px; }
    .hero-glass {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border-radius: 24px; padding: 40px; color: #fff; margin-bottom: 30px;
        position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
    }
    .hero-glass::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .stat-pill {
        background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px;
        padding: 20px; text-align: center; transition: all 0.3s ease;
    }
    .stat-pill:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.3); }
    .stat-pill h3 { font-size: 28px; font-weight: 800; margin: 0; }
    .stat-pill p { font-size: 12px; margin: 0; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

    .section-title { font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    .section-title i { color: #6366f1; }

    .post-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
    .post-card-modern {
        background: #fff; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0;
        transition: all 0.3s ease; display: flex; flex-direction: column;
    }
    .post-card-modern:hover { transform: translateY(-8px); box-shadow: 0 20px 30px rgba(0,0,0,0.05); }
    .post-thumb-modern { width: 100%; height: 180px; object-fit: cover; }
    .post-content-modern { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .post-card-modern h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0; line-height: 1.5; }
    .post-card-modern .meta { font-size: 11px; color: #94a3b8; display: flex; justify-content: space-between; margin-top: auto; }
</style>

<div class="modern-dashboard">
    <div class="hero-glass">
        <div class="row" style="position: relative; z-index: 2;">
            <div class="col-md-5">
                <h1 style="font-weight: 800; margin-bottom: 10px;">Hello, Admin!</h1>
                <p style="opacity: 0.9; margin-bottom: 25px;">Welcome back to your command center. Everything is running smoothly today.</p>
                <a href="<?= adminUrl('add-post'); ?>" class="btn btn-default" style="border-radius: 12px; font-weight: 700; color: #6366f1; padding: 10px 25px; border:none; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">+ New Article</a>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="stat-pill"><p>Articles</p><h3><?= $postsCount ?? 0; ?></h3></div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="stat-pill"><p>Pending</p><h3><?= $pendingPostsCount ?? 0; ?></h3></div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="stat-pill"><p>Drafts</p><h3><?= $draftsCount ?? 0; ?></h3></div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="stat-pill"><p>Scheduled</p><h3><?= $scheduledPostsCount ?? 0; ?></h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-title"><i class="fa fa-bolt"></i> Global Activity Stream</div>
    
    <div class="post-card-grid">
        <?php if (!empty($latestPosts)): foreach ($latestPosts as $post): ?>
            <div class="post-card-modern">
                <img src="<?= getRealImage($post); ?>" class="post-thumb-modern">
                <div class="post-content-modern">
                    <span style="color: #6366f1; font-weight: 800; font-size: 10px; text-transform: uppercase; margin-bottom: 8px;"><?= esc($post->category_name ?? 'Article'); ?></span>
                    <h4><?= esc(characterLimiter($post->title, 60, '...')); ?></h4>
                    <div class="meta">
                        <span><i class="fa fa-clock-o"></i> <?= timeAgo($post->created_at); ?></span>
                        <a href="<?= adminUrl('edit-post/' . $post->id); ?>" style="color: #6366f1; font-weight: 700; text-decoration: none;">EDIT</a>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <p class="text-muted">No recent activity detected.</p>
        <?php endif; ?>
    </div>
</div>
