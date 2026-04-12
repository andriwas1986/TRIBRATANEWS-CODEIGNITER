<div class="row">
    <div class="col-sm-12">
        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title">Kelola Domain / Cabang</h3>
            </div>
            <div class="card-body">
                <form action="<?= adminUrl('sites/add'); ?>" method="post" class="form-inline" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px;">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Nama Cabang:</label>
                        <input type="text" name="site_name" class="form-control" placeholder="Contoh: Berita Jogja" required>
                    </div>
                    <div class="form-group">
                        <label>Subdomain/Domain:</label>
                        <input type="text" name="domain" class="form-control" placeholder="jogja.website.com" required>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Situs</th>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sites as $site): ?>
                            <tr>
                                <td><?= $site->id; ?></td>
                                <td><strong><?= esc($site->site_name); ?></strong></td>
                                <td>
                                    <a href="http://<?= esc($site->domain); ?>" target="_blank">
                                        <?= esc($site->domain); ?> <i class="fa fa-external-link"></i>
                                    </a>
                                </td>
                                <td><span class="label badge bg-success">Aktif</span></td>
                                <td>
                                    <?php if($site->id != 1): ?>
                                    <a href="<?= adminUrl('sites/delete/' . $site->id); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Hapus domain ini?');">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Master</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

