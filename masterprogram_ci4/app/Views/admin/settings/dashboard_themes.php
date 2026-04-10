<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Tema Dashboard Admin</h3>
            </div>
            <form action="<?= adminUrl('set-dashboard-theme-post'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="row">
                        <!-- Classic Theme -->
                        <div class="col-md-4">
                            <div class="theme-card <?= $adminTheme == 'classic' ? 'active' : ''; ?>">
                                <div class="theme-preview" style="background: #f4f7f6; height: 150px; border: 1px solid #ddd; border-radius: 8px; position:relative; overflow:hidden;">
                                    <div style="background: #3c8dbc; height: 15px; width: 100%;"></div>
                                    <div style="background: #222d32; height: 100%; width: 25%; position:absolute;"></div>
                                    <div style="padding: 20px 10px 10px 30%;">
                                         <div style="background: #00a65a; height: 30px; width: 45%; display:inline-block; margin: 2px;"></div>
                                         <div style="background: #f39c12; height: 30px; width: 45%; display:inline-block; margin: 2px;"></div>
                                         <div style="background: #00c0ef; height: 30px; width: 45%; display:inline-block; margin: 2px;"></div>
                                         <div style="background: #dd4b39; height: 30px; width: 45%; display:inline-block; margin: 2px;"></div>
                                    </div>
                                </div>
                                <div class="theme-info" style="padding: 15px 0; text-align:center;">
                                    <h4 style="margin:0; font-weight:700;">Classic Theme</h4>
                                    <p class="text-muted" style="font-size: 13px;">Tampilan standar AdminLTE dengan 4 kotak statistik di atas.</p>
                                    <label class="btn btn-sm <?= $adminTheme == 'classic' ? 'btn-success' : 'btn-default'; ?>">
                                        <input type="radio" name="admin_theme" value="classic" <?= $adminTheme == 'classic' ? 'checked' : ''; ?> style="display:none;">
                                        <?= $adminTheme == 'classic' ? 'Aktif' : 'Pilih Tema'; ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Theme -->
                        <div class="col-md-4">
                            <div class="theme-card <?= $adminTheme == 'modern' ? 'active' : ''; ?>">
                                <div class="theme-preview" style="background: #ffffff; height: 150px; border: 1px solid #ddd; border-radius: 8px; position:relative; overflow:hidden;">
                                    <div style="padding: 20px; text-align:center;">
                                         <div style="background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%); height: 60px; width: 100%; border-radius: 12px; margin-bottom: 10px;"></div>
                                         <div class="row">
                                              <div class="col-xs-6" style="background: #f8fafc; height: 30px; border-radius: 8px;"></div>
                                              <div class="col-xs-6" style="background: #f8fafc; height: 30px; border-radius: 8px;"></div>
                                         </div>
                                    </div>
                                </div>
                                <div class="theme-info" style="padding: 15px 0; text-align:center;">
                                    <h4 style="margin:0; font-weight:700;">Modern Glass</h4>
                                    <p class="text-muted" style="font-size: 13px;">Gaya modern dengan gradient melengkung dan kartu statistik melayang.</p>
                                    <label class="btn btn-sm <?= $adminTheme == 'modern' ? 'btn-success' : 'btn-default'; ?>">
                                        <input type="radio" name="admin_theme" value="modern" <?= $adminTheme == 'modern' ? 'checked' : ''; ?> style="display:none;">
                                        <?= $adminTheme == 'modern' ? 'Aktif' : 'Pilih Tema'; ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- WordPress Theme -->
                        <div class="col-md-4">
                            <div class="theme-card <?= $adminTheme == 'wordpress' ? 'active' : ''; ?>">
                                <div class="theme-preview" style="background: #f1f1f1; height: 150px; border: 1px solid #ddd; border-radius: 8px; position:relative; overflow:hidden;">
                                    <div style="background: #23282d; height: 100%; width: 20%; position:absolute;"></div>
                                    <div style="padding: 15px 10px 10px 25%;">
                                         <div style="background: #fff; height: 40px; width: 100%; border: 1px solid #ddd; margin-bottom: 5px;"></div>
                                         <div style="background: #fff; height: 70px; width: 60%; border: 1px solid #ddd; display:inline-block;"></div>
                                         <div style="background: #fff; height: 70px; width: 35%; border: 1px solid #ddd; display:inline-block; margin-left:2px;"></div>
                                    </div>
                                </div>
                                <div class="theme-info" style="padding: 15px 0; text-align:center;">
                                    <h4 style="margin:0; font-weight:700;">WP Style</h4>
                                    <p class="text-muted" style="font-size: 13px;">Desain fungsional ala WordPress (At a Glance & Quick Draft).</p>
                                    <label class="btn btn-sm <?= $adminTheme == 'wordpress' ? 'btn-success' : 'btn-default'; ?>">
                                        <input type="radio" name="admin_theme" value="wordpress" <?= $adminTheme == 'wordpress' ? 'checked' : ''; ?> style="display:none;">
                                        <?= $adminTheme == 'wordpress' ? 'Aktif' : 'Pilih Tema'; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="text-align: right;">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Terapkan Tema Global</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.theme-card { border: 2px solid transparent; padding: 10px; border-radius: 12px; transition: all 0.3s ease; cursor: pointer; }
.theme-card:hover { background: #f8fafc; transform: translateY(-5px); }
.theme-card.active { border-color: #00a8ff; background: #e0f2ff; }
.theme-preview { transition: all 0.3s ease; }
.theme-card:hover .theme-preview { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
</style>

<script>
    document.querySelectorAll('.theme-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            card.querySelector('input').checked = true;
        });
    });
</script>
