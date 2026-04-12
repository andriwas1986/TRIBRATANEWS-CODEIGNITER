<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title"><?= trans('update_album'); ?></h3>
            </div>
            <form action="<?= base_url('Gallery/editAlbumPost'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" value="<?= $album->id; ?>">
                <div class="card-body">
                    <div class="form-group">
                        <label><?= trans("language"); ?></label>
                        <select name="lang_id" class="form-control">
                            <?php foreach ($activeLanguages as $language): ?>
                                <option value="<?= $language->id; ?>" <?= $album->lang_id == $language->id ? 'selected' : ''; ?>><?= $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?= trans('album_name'); ?></label>
                        <input type="text" class="form-control" name="name" placeholder="<?= trans('album_name'); ?>" value="<?= esc($album->name); ?>" maxlength="200" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-end"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

