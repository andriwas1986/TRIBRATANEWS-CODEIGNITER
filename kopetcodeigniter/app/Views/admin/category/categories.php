<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header with-border">
                <div class="left">
                    <h3 class="card-title"><?= trans("categories"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?= adminUrl('export-categories'); ?>" class="btn btn-info btn-add-new" style="margin-right: 5px;"><i class="fa fa-download"></i> Export CSV</a>
                    
                    <button type="button" class="btn btn-warning btn-add-new" data-toggle="modal" data-target="#modalImportCategory" style="margin-right: 5px;"><i class="fa fa-upload"></i> Import CSV</button>
                    
                    <a href="<?= adminUrl('add-category'); ?>?type=parent" class="btn btn-success btn-add-new"><i class="fa fa-plus"></i><?= trans("add_category"); ?></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <div class="row table-filter-container">
                                    <div class="col-sm-12">
                                        <form action="<?= adminUrl('categories'); ?>" method="get">
                                            <div class="item-table-filter" style="width: 80px; min-width: 80px;">
                                                <label><?= trans("show"); ?></label>
                                                <select name="show" class="form-control">
                                                    <option value="15" <?= inputGet('show', true) == '15' ? 'selected' : ''; ?>>15</option>
                                                    <option value="30" <?= inputGet('show', true) == '30' ? 'selected' : ''; ?>>30</option>
                                                    <option value="60" <?= inputGet('show', true) == '60' ? 'selected' : ''; ?>>60</option>
                                                    <option value="100" <?= inputGet('show', true) == '100' ? 'selected' : ''; ?>>100</option>
                                                </select>
                                            </div>
                                            <div class="item-table-filter">
                                                <label><?= trans("language"); ?></label>
                                                <select name="lang_id" class="form-control" onchange="getParentCategoriesByLang(this.value);">
                                                    <option value=""><?= trans("all"); ?></option>
                                                    <?php foreach ($activeLanguages as $language): ?>
                                                        <option value="<?= $language->id; ?>" <?= inputGet('lang_id') == $language->id ? 'selected' : ''; ?>><?= esc($language->name); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="item-table-filter">
                                                <label><?= trans('parent_category'); ?></label>
                                                <select id="categories" name="category" class="form-control" onchange="getSubCategories(this.value);">
                                                    <option value=""><?= trans("all"); ?></option>
                                                    <?php if (!empty($parentCategories)):
                                                        foreach ($parentCategories as $item): ?>
                                                            <option value="<?= $item->id; ?>" <?= inputGet('category') == $item->id ? 'selected' : ''; ?>>
                                                                <?= esc($item->name); ?>
                                                            </option>
                                                        <?php endforeach;
                                                    endif; ?>
                                                </select>
                                            </div>
                                            <div class="item-table-filter">
                                                <label><?= trans("search"); ?></label>
                                                <input name="q" class="form-control" placeholder="<?= trans("search") ?>" type="search" value="<?= esc(inputGet('q', true)); ?>">
                                            </div>
                                            <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
                                                <label style="display: block">&nbsp;</label>
                                                <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <thead>
                                <tr role="row">
                                    <th width="20"><?= trans('id'); ?></th>
                                    <th><?= trans('category_name'); ?></th>
                                    <th><?= trans('language'); ?></th>
                                    <th><?= trans('parent_category'); ?></th>
                                    <th><?= trans('order_1'); ?></th>
                                    <th><?= trans('color'); ?></th>
                                    <th><?= trans('status'); ?></th>
                                    <th class="max-width-120"><?= trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($categories)):
                                    foreach ($categories as $item): ?>
                                        <tr>
                                            <td><?= esc($item->id); ?></td>
                                            <td><?= esc($item->name); ?></td>
                                            <td>
                                                <?php $lang = getLanguage($item->lang_id);
                                                if (!empty($lang)) {
                                                    echo esc($lang->name);
                                                } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $category = getCategoryById($item->parent_id);
                                                if (!empty($category)) {
                                                    echo esc($category->name);
                                                } else {
                                                    echo '-';
                                                } ?>
                                            </td>
                                            <td><?= esc($item->category_order); ?></td>
                                            <td>
                                                <div style="width: 30px; height: 30px; border-radius: 50%; background-color:<?= esc($item->color); ?> ;"></div>
                                            </td>
                                            <td>
                                                <?php if ($item->category_status == 1): ?>
                                                    <label class="label badge bg-success"><?= trans("active"); ?></label>
                                                <?php else: ?>
                                                    <label class="label badge bg-danger"><?= trans("inactive"); ?></label>
                                                <?php endif; ?>
                                            </td>
                                            <td class="td-select-option">
                                                <div class="dropdown">
                                                    <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?= trans('select_an_option'); ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu options-dropdown">
                                                        <li>
                                                            <a href="<?= adminUrl('edit-category/' . $item->id); ?>"><i class="fa fa-edit option-icon"></i><?= trans('edit'); ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="deleteItem('Category/deleteCategoryPost','<?= $item->id; ?>','<?= clrQuotes(trans("confirm_category")); ?>');"><i class="fa fa-trash option-icon"></i><?= trans('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                            <?php if (empty($categories)): ?>
                                <p class="text-center text-muted"><?= trans("no_records_found"); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <?= $pager->links; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalImportCategory" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= adminUrl('import-categories'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import Kategori (Format CSV)</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih File (.csv)</label>
                        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
                        <small class="text-muted" style="display:block; margin-top:10px;">
                            <b>Tips:</b> Lakukan klik <i>Export CSV</i> terlebih dahulu untuk mendapatkan contoh format kolom (id, lang_id, name, slug, dll) yang benar sebelum melakukan Import.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

