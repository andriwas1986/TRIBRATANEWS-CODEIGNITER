<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="card card-primary">
            <div class="card-header with-border">
                <div class="left">
                    <h3 class="card-title"><?= trans("add_user"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?= adminUrl('users'); ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-bars"></i>
                        <?= trans("users"); ?>
                    </a>
                </div>
            </div>
            <form action="<?= base_url('Admin/addUserPost'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label><?= trans("username"); ?></label>
                        <input type="text" name="username" class="form-control auth-form-input" placeholder="<?= trans("username"); ?>" value="<?= old("username"); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><?= trans("email"); ?></label>
                        <input type="email" name="email" class="form-control auth-form-input" placeholder="<?= trans("email"); ?>" value="<?= old("email"); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><?= trans("password"); ?></label>
                        <input type="password" name="password" class="form-control auth-form-input" placeholder="<?= trans("password"); ?>" value="<?= old("password"); ?>" required>
                    </div>
                    <div class="form-group">
                        <label><?= trans("role"); ?></label>
                        <select name="role_id" class="form-control">
                            <option value=""><?= trans("select"); ?></option>
                            <?php if (!empty($roles)):
                                foreach ($roles as $role):
                                    $roleName = parseSerializedNameArray($role->role_name, $activeLang->id); ?>
                                    <option value="<?= $role->id; ?>"><?= esc($roleName); ?></option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-end"><?= trans('add_user'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

