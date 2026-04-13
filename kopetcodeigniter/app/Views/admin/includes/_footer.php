            </section>
        </div>
    </main>
    <footer id="footer" class="app-footer">
        <div class="float-end d-none d-sm-inline">
            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-3 py-2">
                <i class="fa fa-info-circle"></i> Bootstrap v5.3.3 & AdminLTE v4.0.0
            </span>
            <strong class="ms-3 fw-semibold"><?= isset($baseSettings) ? $baseSettings->copyright : ''; ?>&nbsp;</strong>
        </div>
        <div class="d-inline">
            <b>Andri Solution 08113647707 - APP Version 5.3.3</b>
        </div>
    </footer>
</div>
<script src="<?= base_url('assets/admin/js/jquery-ui.min.js'); ?>"></script>
<script>$.widget.bridge('uibutton', $.ui.button);</script>
<script src="<?= base_url('assets/admin/plugins/bootstrap5/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/adminlte4.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/lazysizes.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/pace/pace.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/file-manager/file-manager-2.4.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/file-uploader/js/jquery.dm-uploader.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/file-uploader/js/ui.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/plugins-2.4.2.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/select2/js/select2.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap-datetimepicker/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/custom-2.4.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/post-types-2.4.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/tinymce-7.6.1/tinymce.min.js'); ?>"></script>

<script>
    function initTinyMCE(selector, minHeight) {
        var menuBar = 'file insert format table help';
        if (selector == '.tinyMCEQuiz') {
            menuBar = false;
        }
        tinymce.init({
            license_key: 'gpl',
            sandbox_iframes: false,
            selector: selector,
            height: minHeight,
            min_height: minHeight,
            valid_elements: '*[*]',
            relative_urls: false,
            entity_encoding: 'raw',
            remove_script_host: false,
            directionality: typeof VrConfig !== 'undefined' ? VrConfig.directionality : 'ltr',
            language: '<?= isset($activeLang) ? $activeLang->text_editor_lang : 'en'; ?>',
            menubar: menuBar,

            // 1. Matikan menu klik kanan TinyMCE (agar bisa Paste lewat Klik Kanan Mouse)
            contextmenu: false, 

            // 2. Aktifkan fitur paste gambar & format
            paste_data_images: true,
            paste_as_text: false,
            smart_paste: true,

            plugins: 'advlist autolink lists link image charmap preview searchreplace visualblocks code codesample fullscreen insertdatetime media table',
            
            // 3. Toolbar: Saya tambahkan tombol 'paste' (ikon papan klip)
            // Urutan: Fullscreen | Undo Redo | PASTE | Bold ...
            toolbar: 'fullscreen code preview | undo redo | paste | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor removeformat | image media link',
            
            content_css: ['<?= base_url('assets/admin/plugins/tinymce-7.6.1/editor_content.css'); ?>'],
            mobile: {
                menubar: menuBar
            }
        });
    }

    if ($('.tinyMCE').length > 0) {
        initTinyMCE('.tinyMCE', 500);
    }
    if ($('.tinyMCEsmall').length > 0) {
        initTinyMCE('.tinyMCEsmall', 300);
    }
    if ($('.tinyMCEQuiz').length > 0) {
        initTinyMCE('.tinyMCEQuiz', 205);
    }
</script>

<style>.pagination a, .pagination span {
        border-radius: 0 !important;
    }</style>
<?php if (isset($langSearchColumn)): ?>
    <script>
        var table = $('#cs_datatable_lang').DataTable({
            dom: 'l<"#table_dropdown">frtip',
            "order": [[0, "desc"]],
            "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
        });
        $('<label class="table-label"></label>').text('Language').appendTo('#table_dropdown');
        $select = $('<select class="form-control input-sm"></select>').appendTo('#table_dropdown');
        $('<option/>').val('').text('<?= trans("all"); ?>').appendTo($select);
        <?php foreach ($activeLanguages as $lang): ?>
        $('<option/>').val('<?= $lang->name; ?>').text('<?= $lang->name; ?>').appendTo($select);
        <?php endforeach; ?>
        $("#table_dropdown select").change(function () {
            table.column(<?= $langSearchColumn; ?>).search($(this).val()).draw();
        });
    </script>
<?php endif; ?>



</body>
</html>

