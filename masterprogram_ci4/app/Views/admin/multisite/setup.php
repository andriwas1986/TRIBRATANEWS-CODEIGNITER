<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Instalasi Multi-Site</h3>
            </div>
            <div class="box-body text-center" style="padding: 50px;">
                <i class="fa fa-globe" style="font-size: 80px; color: #3c8dbc; margin-bottom: 20px;"></i>
                <h3>Aktifkan Fitur Multi-Tenant?</h3>
                <p>Sistem akan memodifikasi database Anda secara otomatis untuk mendukung banyak domain dalam satu aplikasi.</p>
                <br>
                <form action="<?= adminUrl('multisite-setup/run'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-cogs"></i> Jalankan Setup Otomatis
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>