<div class="row">
    <div class="col-sm-12">
        <form action="<?= base_url('Post/addPostPost'); ?>" method="post" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
            <?= csrf_field(); ?>
            <input type="hidden" name="post_type" value="article">
            <div class="row">
                <div class="col-sm-12 form-header">
                    <h1 class="form-title"><?= trans('add_article'); ?></h1>
                    <a href="<?= adminUrl('posts'); ?>" class="btn btn-success btn-add-new pull-right">
                        <i class="fa fa-bars"></i>
                        <?= trans('posts'); ?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-post">
                        <div class="form-post-left">
                            
                            <?= view("admin/post/_form_add_post_left"); ?>
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= view("admin/post/_text_editor"); ?>
                                </div>
                            </div>

                            <div class="row m-t-15">
                                <div class="col-sm-12">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Pengaturan Tambahan</h3>
                                        </div>
                                        <div class="box-body">
                                            
                                            <div class="form-group">
                                                <label class="control-label"><?= trans('optional_url'); ?></label>
                                                <input type="text" class="form-control" name="optional_url" placeholder="<?= trans('optional_url'); ?>" value="<?= old('optional_url'); ?>">
                                            </div>

                                            <?php if (hasPermission('manage_all_posts')): ?>
                                                <div class="form-group">
                                                    <label><?= trans("visibility"); ?></label>
                                                    <?= formRadio('visibility', 1, 0, trans("show"), trans("hide"), 1, 'col-md-4'); ?>
                                                </div>
                                            <?php else:
                                                if ($generalSettings->approve_added_user_posts == 1): ?>
                                                    <input type="hidden" name="visibility" value="0">
                                                <?php else: ?>
                                                    <input type="hidden" name="visibility" value="1">
                                                <?php endif;
                                            endif; ?>

                                            <?php if ($activeTheme->theme == 'classic'): ?>
                                                <div class="form-group">
                                                    <label><?= trans("show_right_column"); ?></label>
                                                    <?= formRadio('show_right_column', 1, 0, trans("yes"), trans("no"), 1, 'col-md-4'); ?>
                                                </div>
                                            <?php else: ?>
                                                <input type="hidden" name="show_right_column" value="1">
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        
                        <div class="form-post-right">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= view('admin/post/_upload_image_box'); ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= view('admin/post/_upload_additional_image_box'); ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= view('admin/post/_upload_file_box'); ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= view('admin/post/_categories_box'); ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= view('admin/post/_publish_box', ['postType' => 'article']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= view('admin/file-manager/_load_file_manager', ['loadImages' => true, 'loadFiles' => true, 'loadVideos' => false, 'loadAudios' => false]); ?>