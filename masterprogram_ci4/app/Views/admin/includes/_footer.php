<?= $baseAIWriter->status == 1 && hasPermission('ai_writer') ? view('admin/post/_ai_writer') : ''; ?>
</section>
</div>
<footer id="footer" class="main-footer">
    <div class="float-end hidden-xs">
        <span style="background: rgba(0, 168, 255, 0.1); color: #00a8ff; padding: 4px 12px; border-radius: 15px; font-size: 11px; font-weight: 700; border: 1px solid rgba(0, 168, 255, 0.3); margin-right: 15px;">
            <i class="fa fa-info-circle"></i> Bootstrap v5.3.3 & AdminLTE v4.0.0
        </span>
        <strong style="font-weight: 600;"><?= $baseSettings->copyright; ?>&nbsp;</strong>
    </div>
    <b>Andri Solution 08113647707 - APP Version 5.3.3</b>
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
            directionality: VrConfig.directionality,
            language: '<?= $activeLang->text_editor_lang; ?>',
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

<style>
    /* 1. Memperbesar Menu Utama (Home, Navigation, Add Post, dll) */
    .sidebar-menu > li > a {
        font-size: 18px !important; /* Ubah angka ini sesuai selera */
        font-weight: 500 !important; /* Agar agak tebal sedikit */
        padding: 12px 12px !important; /* Menambah jarak biar tidak mepet */
    }

    /* 2. Memperbesar Ikon di Menu */
    .sidebar-menu > li > a > i {
        font-size: 18px !important; /* Ikon juga ikut diperbesar */
        width: 25px !important;
    }

    /* 3. Memperbesar Judul Header (MAIN NAVIGATION) */
    .sidebar-menu .header {
        font-size: 13px !important;
        font-weight: bold !important;
        letter-spacing: 1px;
    }

    /* 4. Memperbesar Sub-Menu (Anak menu jika ada) */
    .sidebar-menu .treeview-menu > li > a {
        font-size: 14px !important;
    }
</style>

</body>
</html>

