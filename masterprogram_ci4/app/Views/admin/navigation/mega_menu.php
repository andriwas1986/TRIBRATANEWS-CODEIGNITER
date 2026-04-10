<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Banner Utama Mega Menu</h3></div>
            <form action="<?= adminUrl('mega-menu-settings-post'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="form_type" value="megamenu">
                <input type="hidden" name="action" value="add" id="form_action">
                <input type="hidden" name="id" value="" id="form_id">
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Slug Menu Utama</label>
                        <input type="text" name="menu_slug" id="form_slug" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gaya Mega Menu</label>
                        <select name="menu_style" id="form_style" class="form-control" required>
                            <option value="banner_left">Banner Kiri & Menu Kanan</option>
                            <option value="banner_right">Menu Kiri & Banner Kanan</option>
                            <option value="standard">Standar Dropdown (Tanpa Gambar)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Judul Banner</label>
                        <input type="text" name="menu_title" id="form_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="menu_desc" id="form_desc" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar Banner</label>
                        <input type="file" name="file_image" class="form-control" accept="image/*">
                        <input type="text" name="menu_image_url" id="form_img_url" class="form-control" placeholder="Atau URL manual..." style="margin-top:5px;">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Simpan Banner</button>
                    <button type="button" class="btn btn-default" onclick="resetFormMega()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Daftar Banner Mega Menu</h3></div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr><th>Slug Menu</th><th>Gaya</th><th>Judul</th><th>Opsi</th></tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($megamenus)): foreach($megamenus as $m): ?>
                                <tr>
                                    <td><strong><?= esc($m->menu_slug); ?></strong></td>
                                    <td><span class="label label-info"><?= esc($m->menu_style); ?></span></td>
                                    <td><?= esc($m->menu_title); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="editMenu('<?= $m->id ?>','<?= $m->menu_slug ?>','<?= $m->menu_style ?>','<?= $m->menu_title ?>','<?= htmlspecialchars($m->menu_desc, ENT_QUOTES) ?>','<?= $m->menu_image ?>')"><i class="fa fa-edit"></i></button>
                                        <form action="<?= adminUrl('mega-menu-settings-post'); ?>" method="post" style="display:inline-block;" onsubmit="return confirm('Hapus?');">
                                            <?= csrf_field(); ?><input type="hidden" name="form_type" value="megamenu"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Pengaturan Sub-Menu (Icon & Teks)</h3></div>
            <form action="<?= adminUrl('mega-menu-settings-post'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="form_type" value="submenu">
                <input type="hidden" name="action" value="add" id="form_sub_action">
                <input type="hidden" name="id" value="" id="form_sub_id">
                
                <div class="box-body">
                    <div class="form-group">
                        <label>Slug Sub-Menu</label>
                        <input type="text" name="sub_slug" id="form_sub_slug" class="form-control" placeholder="Contoh: skck-online" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Bawah (Deskripsi Kecil)</label>
                        <textarea name="sub_desc" id="form_sub_desc" class="form-control" rows="2" placeholder="Contoh: Aplikasi Pembuatan SKCK Secara Online"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Opsi 1: Class Icon FontAwesome</label>
                        <input type="text" name="sub_icon" id="form_sub_icon" class="form-control" placeholder="Contoh: fa fa-id-card-o">
                    </div>

                    <div class="form-group">
                        <label>Opsi 2: Upload Gambar/Logo Icon <span style="color:red; font-weight:normal;">(Timpa Opsi 1)</span></label>
                        <input type="file" name="file_sub_image" class="form-control" accept="image/*">
                        <input type="text" name="sub_image_url" id="form_sub_img_url" class="form-control" placeholder="Atau URL manual gambar..." style="margin-top:5px;">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success pull-right">Simpan Sub-Menu</button>
                    <button type="button" class="btn btn-default" onclick="resetFormSub()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Daftar Setingan Sub-Menu</h3></div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr><th>Slug Sub-Menu</th><th>Preview Icon</th><th>Deskripsi</th><th>Opsi</th></tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($submenus)): foreach($submenus as $s): 
                                $img = isset($s->sub_image) ? $s->sub_image : ''; 
                            ?>
                                <tr>
                                    <td><strong><?= esc($s->sub_slug); ?></strong></td>
                                    <td class="text-center">
                                        <?php if(!empty($img)): ?>
                                            <img src="<?= strpos($img, 'http') === false ? base_url($img) : $img; ?>" style="height:25px; object-fit:contain;">
                                        <?php else: ?>
                                            <i class="<?= esc($s->sub_icon); ?>" style="font-size: 20px; color: #00a8ff;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= isset($s->sub_desc) ? esc($s->sub_desc) : '-'; ?></small></td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="editSub('<?= $s->id ?>','<?= $s->sub_slug ?>','<?= $s->sub_icon ?>','<?= $img ?>','<?= isset($s->sub_desc) ? htmlspecialchars($s->sub_desc, ENT_QUOTES) : '' ?>')"><i class="fa fa-edit"></i></button>
                                        <form action="<?= adminUrl('mega-menu-settings-post'); ?>" method="post" style="display:inline-block;" onsubmit="return confirm('Hapus icon ini?');">
                                            <?= csrf_field(); ?><input type="hidden" name="form_type" value="submenu"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $s->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center">Belum ada icon submenu yang disetting.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editMenu(id, slug, style, title, desc, img) {
    document.getElementById('form_action').value = 'edit';
    document.getElementById('form_id').value = id;
    document.getElementById('form_slug').value = slug;
    document.getElementById('form_style').value = style;
    document.getElementById('form_title').value = title;
    document.getElementById('form_desc').value = desc;
    document.getElementById('form_img_url').value = img;
}
function resetFormMega() {
    document.getElementById('form_action').value = 'add';
    document.getElementById('form_id').value = '';
    document.getElementById('form_slug').value = '';
    document.getElementById('form_title').value = '';
    document.getElementById('form_desc').value = '';
    document.getElementById('form_img_url').value = '';
}
function editSub(id, slug, icon, img, desc) {
    document.getElementById('form_sub_action').value = 'edit';
    document.getElementById('form_sub_id').value = id;
    document.getElementById('form_sub_slug').value = slug;
    document.getElementById('form_sub_icon').value = icon;
    document.getElementById('form_sub_img_url').value = img;
    document.getElementById('form_sub_desc').value = desc;
}
function resetFormSub() {
    document.getElementById('form_sub_action').value = 'add';
    document.getElementById('form_sub_id').value = '';
    document.getElementById('form_sub_slug').value = '';
    document.getElementById('form_sub_icon').value = '';
    document.getElementById('form_sub_img_url').value = '';
    document.getElementById('form_sub_desc').value = '';
}
</script>