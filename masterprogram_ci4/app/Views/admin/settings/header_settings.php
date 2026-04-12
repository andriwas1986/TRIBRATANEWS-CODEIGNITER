<style>
    /* Kerangka Utama Card */
    .header-setting-card { background: #fff; border-radius: 12px; border: none; card-shadow: 0 4px 25px rgba(0,0,0,0.08); margin-bottom: 30px; overflow: hidden; }
    .header-setting-card .card-header { background: #ffffff; border-bottom: 1px solid #f1f4f8; padding: 20px 25px; }
    .header-setting-card .card-title { font-weight: 700; color: #1e293b; font-size: 18px; display: flex; align-items: center; }
    
    /* Styling Grouping Input */
    .setting-group { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 25px; transition: all 0.3s ease; }
    .setting-group:hover { border-color: #3b82f6; background: #ffffff; card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    
    /* Label Kategori */
    .group-label { font-weight: 700; color: #64748b; text-transform: uppercase; font-size: 11px; letter-spacing: 1.5px; margin-bottom: 15px; display: flex; align-items: center; }
    .group-label::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; margin-left: 15px; }
    
    /* Color Picker Wrapper */
    .color-input-wrapper { display: flex; align-items: center; background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 5px 12px; gap: 10px; transition: border 0.2s; }
    .color-input-wrapper:focus-within { border-color: #3b82f6; ring: 2px rgba(59, 130, 246, 0.2); }
    
    .custom-color-picker { width: 35px; height: 35px; border: none; border-radius: 6px; cursor: pointer; padding: 0; background: none; }
    .custom-color-picker::-webkit-color-swatch-wrapper { padding: 0; }
    .custom-color-picker::-webkit-color-swatch { border: none; border-radius: 6px; }
    
    .hex-value { font-family: 'Monaco', 'Consolas', monospace; font-weight: 600; color: #334155; border: none; background: transparent; padding: 0; width: 80px; text-transform: uppercase; outline: none; }

    /* Buttons */
    .btn-modern-save { background: #00a8ff; color: #fff; border: none; border-radius: 8px; padding: 12px 35px; font-weight: 700; font-size: 14px; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-modern-save:hover { background: #0097e6; transform: translateY(-2px); card-shadow: 0 8px 15px rgba(0, 168, 255, 0.3); color: #fff; }
    
    .btn-modern-reset { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 20px; font-weight: 600; transition: all 0.2s; }
    .btn-modern-reset:hover { background: #fecaca; color: #b91c1c; }

    /* Image Preview */
    .preview-img-container { position: relative; width: fit-content; margin-top: 12px; border-radius: 10px; overflow: hidden; border: 2px dashed #cbd5e1; background: #f1f5f9; padding: 8px; }
    .preview-img-container img { border-radius: 6px; max-width: 100%; object-fit: contain; }
</style>

<div class="row">
    <div class="col-md-11 col-lg-10 col-sm-12">
        <div class="card header-setting-card">
            <div class="card-header">
                <h3 class="card-title">
                    <span style="background: #e0f2fe; padding: 8px; border-radius: 8px; margin-right: 12px;">
                        <i class="fa fa-paint-brush" style="color: #00a8ff;"></i>
                    </span>
                    Visual Header & Typography Settings
                </h3>
            </div>
            
            <form action="<?= adminUrl('header-settings-post'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="card-body" style="padding: 30px;">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <span class="group-label">Top Menu Bar</span>
                            <div class="setting-group">
                                <div class="form-group">
                                    <label style="font-size: 13px; color: #475569;">Background Color</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="top_menu_color" value="<?= esc($headerSetting->top_menu_color); ?>" class="custom-color-picker js-color-picker">
                                        <input type="text" class="hex-value js-color-value" value="<?= esc($headerSetting->top_menu_color); ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:20px;">
                                    <label style="font-size: 13px; color: #475569;">Links & Text Color</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="top_menu_text" value="<?= $headerSetting->top_menu_text ?? '#333333'; ?>" class="custom-color-picker js-color-picker">
                                        <input type="text" class="hex-value js-color-value" value="<?= $headerSetting->top_menu_text ?? '#333333'; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <span class="group-label">Main Navigation Bar</span>
                            <div class="setting-group">
                                <div class="form-group">
                                    <label style="font-size: 13px; color: #475569;">Background Color</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="main_menu_color" value="<?= esc($headerSetting->main_menu_color); ?>" class="custom-color-picker js-color-picker">
                                        <input type="text" class="hex-value js-color-value" value="<?= esc($headerSetting->main_menu_color); ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:20px;">
                                    <label style="font-size: 13px; color: #475569;">Links & Text Color</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="main_menu_text" value="<?= $headerSetting->main_menu_text ?? '#ffffff'; ?>" class="custom-color-picker js-color-picker">
                                        <input type="text" class="hex-value js-color-value" value="<?= $headerSetting->main_menu_text ?? '#ffffff'; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="group-label" style="margin-top: 10px;">Logo & Banner Identity Area</span>
                    <div class="setting-group">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label style="font-size: 13px; color: #475569;">Fallback Background Color</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="bg_color" value="<?= esc($headerSetting->bg_color); ?>" class="custom-color-picker js-color-picker">
                                        <input type="text" class="hex-value js-color-value" value="<?= esc($headerSetting->bg_color); ?>" readonly>
                                    </div>
                                    <p class="small text-muted" style="margin-top: 8px;">Warna ini muncul jika gambar tidak ada atau sedang loading.</p>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label style="font-size: 13px; color: #475569;">Header Background Image</label>
                                    <input type="file" name="bg_image" class="form-control" style="border-radius: 8px; padding: 8px; height: auto;">
                                    
                                    <?php if(!empty($headerSetting->bg_image)): ?>
                                        <div class="preview-img-container">
                                            <img src="<?= base_url($headerSetting->bg_image); ?>" style="max-height: 100px;">
                                        </div>
                                        <div class="checkbox" style="margin-top: 12px;">
                                            <label style="color: #ef4444; font-weight: 700; cursor: pointer;">
                                                <input type="checkbox" name="remove_image" value="1"> 
                                                <i class="fa fa-trash-o" style="margin-right: 5px;"></i> Hapus Gambar Latar
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="card-footer" style="background: #f8fafc; padding: 25px 30px; border-top: 1px solid #e2e8f0;">
                    <button type="submit" name="submit" value="save" class="btn btn-modern-save">
                        <i class="fa fa-check-circle"></i> Simpan Konfigurasi
                    </button>
                    
                    <button type="submit" name="submit" value="reset" class="btn btn-modern-reset float-end" onclick="return confirm('Apakah Anda yakin ingin mereset semua warna ke pengaturan awal?')">
                        <i class="fa fa-refresh"></i> Reset Ke Default
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script untuk membuat input text hex sinkron dengan picker warna secara Live
    document.querySelectorAll('.js-color-picker').forEach(function(picker) {
        picker.addEventListener('input', function() {
            let hexInput = this.parentElement.querySelector('.js-color-value');
            hexInput.value = this.value.toUpperCase();
        });
    });
</script>

