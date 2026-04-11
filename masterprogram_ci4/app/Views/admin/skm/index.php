<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= $title; ?></h3>
        </div>
        <div class="right">
            <a href="<?= adminUrl('skm/statistics'); ?>" class="btn btn-sm btn-info btn-add-new">
                <i class="fa fa-line-chart"></i>&nbsp;&nbsp;Lihat Statistik SKM
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid" aria-describedby="example1_info">
                        <thead>
                        <tr role="row">
                            <th width="20">ID</th>
                            <th>Nama</th>
                            <th>Layanan</th>
                            <th>Skor (Avg)</th>
                            <th>Saran</th>
                            <th>Tanggal</th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($surveys)):
                            foreach ($surveys as $item): ?>
                                <tr>
                                    <td><?= esc($item->id); ?></td>
                                    <td>
                                        <strong><?= esc($item->name ?: 'Anonim'); ?></strong><br>
                                        <small><?= esc($item->phone); ?></small>
                                    </td>
                                    <td><?= esc($item->service_type); ?></td>
                                    <td>
                                        <span class="label label-<?= $item->average_score >= 3 ? 'success' : ($item->average_score >= 2 ? 'warning' : 'danger'); ?>" style="font-size: 13px;">
                                            <?= esc($item->average_score); ?> / 4.00
                                        </span>
                                    </td>
                                    <td style="max-width: 300px; word-wrap: break-word;"><?= esc($item->suggestion); ?></td>
                                    <td><?= formatDate($item->created_at); ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn bg-purple btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                                <?= trans('options'); ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu options-dropdown">
                                                <li>
                                                    <a href="javascript:void(0)" onclick="deleteItem('AdminSkmController/delete','<?= $item->id; ?>','Apakah Anda yakin ingin menghapus data survei ini?');">
                                                        <i class="fa fa-trash option-icon"></i><?= trans('delete'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
