<?php if (hasPermission('manage_all_posts')):
    $author = getUserById($post->user_id); ?>
    <div class="card">
        <div class="card-header with-border">
            <div class="left">
                <h3 class="card-title"><?= trans('post_owner'); ?></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group m-0">
                <label><?= trans("post_owner"); ?></label>
                <select name="user_id" class="form-control select2-users" required>
                    <?php if (!empty($author)): ?>
                        <option value="<?= $author->id; ?>" selected><?= esc($author->username); ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
<?php else: ?>
    <input type="hidden" name="user_id" value="<?= $post->user_id; ?>">
<?php endif; ?>

