<?php
    // --- [1] LOGIC PHP: Same statistics logic from index ---
    $db = \Config\Database::connect();
    $latestPosts = $db->table('posts')
        ->select('posts.*, categories.name as category_name, 
                  images.image_mid, images.image_small, images.image_default, images.file_name as img_filename')
        ->join('categories', 'categories.id = posts.category_id', 'left')
        ->join('images', 'images.id = posts.image_id', 'left')
        ->orderBy('posts.created_at', 'DESC')
        ->limit(5)
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
    .wp-dashboard { background: #f1f1f1; padding: 20px 20px 50px 20px; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; color: #3c434a; margin-top: -20px; margin-left: -15px; margin-right: -15px;}
    .wp-title { font-size: 23px; font-weight: 400; margin: 0 0 20px 0; }
    .wp-box { background: #fff; border: 1px solid #c3c4c7; border-radius: 0; margin-bottom: 20px; box-shadow: none; }
    .wp-box-header { border-bottom: 1px solid #c3c4c7; padding: 12px 15px; }
    .wp-box-header h2 { margin: 0; font-size: 14px; font-weight: 600; color: #1d2327; }
    .wp-box-content { padding: 15px; }
    
    .at-a-glance ul { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; }
    .at-a-glance li { width: 50%; margin-bottom: 5px; font-size: 13px; }
    .at-a-glance li i { color: #8c8f94; margin-right: 5px; width: 16px; text-align: center; }
    .at-a-glance li a { color: #2271b1; text-decoration: none; font-weight: 600; }
    
    .activity-list { list-style: none; padding: 0; margin: 0; }
    .activity-item { padding: 10px 0; border-bottom: 1px solid #f0f0f1; font-size: 13px; }
    .activity-item:last-child { border: none; }
    .activity-date { color: #646970; font-size: 12px; }
    
    .quick-draft label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 3px; }
    .quick-draft input, .quick-draft textarea { width: 100%; border: 1px solid #8c8f94; padding: 6px 10px; font-size: 14px; margin-bottom: 15px; border-radius: 3px; }
    .btn-wp { background: #2271b1; color: #fff; border: 1px solid #2271b1; padding: 6px 15px; border-radius: 3px; font-size: 13px; cursor: pointer; }
    .btn-wp:hover { background: #135e96; }
</style>

<div class="wp-dashboard">
    <h1 class="wp-title">Dashboard <small style="font-size: 12px; color: #646970;">WordPress Style</small></h1>

    <div class="row">
        <div class="col-md-6">
            <!-- At a Glance -->
            <div class="wp-box at-a-glance">
                <div class="wp-box-header"><h2>At a Glance</h2></div>
                <div class="wp-box-content">
                    <ul>
                        <li><i class="fa fa-newspaper-o"></i> <a href="<?= adminUrl('posts'); ?>"><?= $postsCount ?? 0; ?> Posts</a></li>
                        <li><i class="fa fa-file-text-o"></i> <a href="<?= adminUrl('pages'); ?>">Pages</a></li>
                        <li><i class="fa fa-comments"></i> <a href="<?= adminUrl('comments'); ?>">Comments</a></li>
                        <li><i class="fa fa-folder-open"></i> <a href="<?= adminUrl('categories'); ?>">Categories</a></li>
                    </ul>
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f1; font-size: 13px;">
                        POLRES MADIUN v5.3.3 is running Royal Luxury Theme.
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <div class="wp-box">
                <div class="wp-box-header"><h2>Recent Activity</h2></div>
                <div class="wp-box-content">
                    <div style="font-size: 12px; color: #646970; margin-bottom: 10px;">Recently Published</div>
                    <ul class="activity-list">
                        <?php if (!empty($latestPosts)): foreach ($latestPosts as $post): ?>
                            <li class="activity-item">
                                <span class="activity-date"><?= date('M jS, g:i a', strtotime($post->created_at)); ?></span> – 
                                <a href="<?= base_url($post->slug); ?>" target="_blank" style="color: #2271b1; font-weight: 600;"><?= esc(characterLimiter($post->title, 70, '...')); ?></a>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Quick Draft -->
            <div class="wp-box quick-draft">
                <div class="wp-box-header"><h2>Quick Draft</h2></div>
                <div class="wp-box-content">
                    <form onsubmit="return false;">
                        <label>Title</label>
                        <input type="text" placeholder="What’s on your mind?">
                        <label>Content</label>
                        <textarea rows="4" placeholder="Draft your idea here..."></textarea>
                        <button class="btn-wp">Save Draft</button>
                    </form>
                </div>
            </div>

            <!-- System Info -->
            <div class="wp-box">
                <div class="wp-box-header"><h2>WordPress Events and News</h2></div>
                <div class="wp-box-content">
                    <div style="text-align: center; padding: 20px; color: #646970;">
                        <i class="fa fa-wordpress" style="font-size: 40px; color: #dcdcde;"></i>
                        <p style="margin-top: 10px; font-size: 13px;">Police Website Platform is up to date.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
