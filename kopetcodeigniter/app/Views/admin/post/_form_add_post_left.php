<div class="card">
    <div class="card-header with-border">
        <div class="left">
            <h3 class="card-title"><?= trans('post_details'); ?></h3>
        </div>
    </div>
    <div class="card-body">
        
        <div class="form-group">
            <label class="control-label"><?= trans('title'); ?></label>
            <input type="text" id="wr_input_post_title" class="form-control" name="title" placeholder="<?= trans('title'); ?>" value="<?= old('title'); ?>" required>
        </div>

        <div class="form-group" style="display: none;">
            <label class="control-label"><?= trans('slug'); ?></label>
            <input type="text" class="form-control" name="slug" placeholder="<?= trans('slug'); ?>" value="<?= old('slug'); ?>">
        </div>

        <div class="form-group" style="display: none;">
            <label class="control-label"><?= trans('summary'); ?></label>
            <textarea class="form-control text-area" name="summary" placeholder="<?= trans('summary'); ?>"><?= old('summary'); ?></textarea>
        </div>
        <div class="form-group">
            <label class="control-label"><?= trans('keywords'); ?> (<?= trans('meta_tag'); ?>)</label>
            <input type="text" class="form-control" name="keywords" placeholder="<?= trans('keywords'); ?> (<?= trans('meta_tag'); ?>)" value="<?= old('keywords'); ?>">
        </div>

        <?php if (!empty($postType) && $postType == 'poll'): ?>
            <div class="form-group">
                <label><?= trans("vote_permission"); ?></label>
                <?= formRadio('vote_permission', 'registered', 'all', trans("registered_users_can_vote"), trans("all_users_can_vote"), 'all', 'col-md-4'); ?>
            </div>
        <?php endif; ?>

        <?php if (hasPermission('manage_all_posts')): ?>
            <input type="hidden" name="is_slider" value="1">
            <input type="hidden" name="is_featured" value="1">
            <input type="hidden" name="is_breaking" value="1">
            <input type="hidden" name="is_recommended" value="1">
        <?php else: ?>
            <input type="hidden" name="is_slider" value="0">
            <input type="hidden" name="is_featured" value="0">
            <input type="hidden" name="is_breaking" value="0">
            <input type="hidden" name="is_recommended" value="0">
        <?php endif; ?>
        
        <input type="hidden" name="need_auth" value="0">

        <?php if ($postType == 'sorted_list' || $postType == 'gallery'): ?>
            <div class="form-group">
                <?= formCheckbox('show_item_numbers', 1, trans("show_item_numbers"), 1); ?>
            </div>
        <?php endif; ?>

        <div class="form-group m-t-30">
            <?= view("admin/post/_tags_input"); ?>
        </div>

        <?php if ($postType == 'table_of_contents'): ?>
            <p class="m-t-30" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                <strong class="font-weight-600"><?= trans("link_list_style"); ?></strong>
            </p>
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label><?= trans("level_1"); ?></label>
                        <select name="link_list_style_1" class="form-control" required>
                            <?php foreach (getCssListStyles() as $style): ?>
                                <option value="<?= $style; ?>" <?= $style == 'decimal' ? 'selected' : ''; ?>><?= $style; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?= formCheckbox('link_list_style_show_1', 1, trans("show_list_style_post_text"), 0); ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label><?= trans("level_2"); ?></label>
                        <select name="link_list_style_2" class="form-control" required>
                            <?php foreach (getCssListStyles() as $style): ?>
                                <option value="<?= $style; ?>" <?= $style == 'disc' ? 'selected' : ''; ?>><?= $style; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?= formCheckbox('link_list_style_show_2', 1, trans("show_list_style_post_text"), 0); ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label><?= trans("level_3"); ?></label>
                        <select name="link_list_style_3" class="form-control" required>
                            <?php foreach (getCssListStyles() as $style): ?>
                                <option value="<?= $style; ?>" <?= $style == 'square' ? 'selected' : ''; ?>><?= $style; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?= formCheckbox('link_list_style_show_3', 1, trans("show_list_style_post_text"), 0); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($postType == 'recipe'): ?>
             <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?= trans('prep_time'); ?>&nbsp;<small>(<?= trans('minutes'); ?>)</small></label>
                        <input type="number" class="form-control" name="prep_time" min="0" max="999999" placeholder="<?= trans('prep_time'); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?= trans('cook_time'); ?>&nbsp;<small>(<?= trans('minutes'); ?>)</small></label>
                        <input type="number" class="form-control" name="cook_time" min="0" max="999999" placeholder="<?= trans('cook_time'); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?= trans('serving'); ?></label>
                        <input type="number" class="form-control" name="serving" min="0" max="999999" placeholder="<?= trans('serving'); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?= trans('difficulty'); ?></label>
                        <select name="difficulty" class="form-control">
                            <option value="1" selected><?= trans("easy"); ?></option>
                            <option value="2"><?= trans("intermediate"); ?></option>
                            <option value="3"><?= trans("advanced"); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

