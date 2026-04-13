<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title); ?> - <?= trans("admin"); ?>&nbsp;<?= isset($baseSettings) ? esc($baseSettings->site_title) : ''; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= csrf_meta(); ?>
    <link rel="shortcut icon" type="image/png" href="<?= getFavicon(); ?>"/>
    <?php 
    $adminTheme = $adminTheme ?? 'classic';
    $activeLang = $activeLang ?? (object)['id' => 1, 'text_direction' => 'ltr', 'name' => 'English'];
    ?>
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/bootstrap5/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/adminlte4.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/overlay-scrollbars/OverlayScrollbars.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables/dataTables.bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables/jquery.dataTables_themeroller.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/pace/pace.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/select2/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/file-manager/file-manager-2.4.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/file-uploader/css/jquery.dm-uploader.min.css'); ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/file-uploader/css/styles-1.0.css'); ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/custom-2.4.min.css'); ?>">
    <script src="<?= base_url('assets/admin/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/js/jquery-migrate.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/plugins/overlay-scrollbars/OverlayScrollbars.min.js'); ?>"></script>
    <script>$(function () {
            $('.sidebar-scrollbar').overlayScrollbars({});
        });</script>
    <?php if ($activeLang->text_direction == 'rtl'): ?>
        <link href="<?= base_url('assets/admin/css/rtl-2.4.min.css'); ?>" rel="stylesheet"/>
    <?php endif; ?>
    <script>var VrConfig = {baseURL: '<?= base_url(); ?>', csrfTokenName: '<?= csrf_token() ?>', sysLangId: '<?= $activeLang->id; ?>', directionality: "<?= $activeLang->text_direction == 'rtl' ? 'rtl' : 'ltr'; ?>", textSelectImage: "<?= clrDQuotes(trans("select_image")); ?>", textSelect: "<?= clrDQuotes(trans("select")); ?>", textOk: "<?= clrDQuotes(trans("ok")); ?>", textYes: "<?= clrDQuotes(trans("yes")); ?>", textCancel: "<?= clrDQuotes(trans("cancel")); ?>", textEnter2Characters: "<?= clrDQuotes(trans("enter_2_characters")); ?>", textSearching: "<?= clrDQuotes(trans("searching")); ?>", textNoResult: "<?= clrDQuotes(trans("search_noresult")); ?>", textProcessing: "<?= clrDQuotes(trans("txt_processing")); ?>", textTopicEmpty: "<?= clrDQuotes(trans("msg_topic_empty")); ?>"};</script>
    <script>
        function setAjaxData(object = null) {
            var data = {
                'sysLangId': VrConfig.sysLangId,
            };
            data[VrConfig.csrfTokenName] = $('meta[name="X-CSRF-TOKEN"]').attr('content');
            if (object != null) {
                Object.assign(data, object);
            }
            return data;
        }

        function setSerializedData(serializedData) {
            serializedData.push({name: 'sysLangId', value: VrConfig.sysLangId});
            serializedData.push({name: VrConfig.csrfTokenName, value: $('meta[name="X-CSRF-TOKEN"]').attr('content')});
            return serializedData;
        }
    </script>
    <?php 
    $isAdminPage = false;
    $requestUri = $_SERVER['REQUEST_URI'];
    if (strpos($requestUri, '/admin') !== false || strpos($requestUri, '/pokok-awuren') !== false) { $isAdminPage = true; }
    if (isset($adminTheme)) { $isAdminPage = true; }
    if ($isAdminPage): ?>
    <style>
        /* --- COMPATIBILITY LAYER: AdminLTE 2 to 4 --- */
        body { font-size: 14px !important; line-height: 1.42857143 !important; color: #333 !important; background-color: #f4f6f9 !important; }
        .app-main { flex: 1; padding: 20px; background: #f4f6f9; min-height: calc(100vh - 120px); }
        .content-wrapper { background: transparent !important; margin-left: 0 !important; padding: 0 !important; min-height: auto !important; }
        .row { display: flex; flex-wrap: wrap; margin-right: -7.5px; margin-left: -7.5px; }
        .col-sm-12, .col-md-12, .col-lg-12 { width: 100%; padding: 0 7.5px; }
        .float-right-container { float: right; }
        
        /* Font Scaling Resets */
        h1, h2, h3, h4, h5, h6 { margin-top: 0; margin-bottom: 10px; font-weight: 500; }
        h1 { font-size: 24px; } h2 { font-size: 20px; } h3 { font-size: 18px; } h4 { font-size: 16px; } h5 { font-size: 14px; } h6 { font-size: 12px; }
        
        /* Legacy Panels & Boxes */
        .panel { margin-bottom: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05); }
        .panel-heading { padding: 10px 15px; border-bottom: 1px solid #ddd; border-top-left-radius: 3px; border-top-right-radius: 3px; }
        .panel-title { margin-top: 0; margin-bottom: 0; font-size: 14px !important; color: inherit; }
        .panel-body { padding: 15px; }
        .list-group-item { padding: 10px 15px; margin-bottom: -1px; background-color: #fff; border: 1px solid #ddd; font-size: 14px !important; }

        /* Legacy AdminLTE 2 classes support */
        .box { position: relative; border-radius: 8px; background: #ffffff; border-top: 3px solid #d2d6de; margin-bottom: 20px; width: 100%; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
        .box-header { color: #444; display: block; padding: 10px; position: relative; border-bottom: 1px solid #f4f4f4; }
        .box-header .box-title { display: inline-block; font-size: 16px; margin: 0; line-height: 1; }
        .box-body { border-radius: 0 0 3px 3px; padding: 10px; }
        .pull-right { float: right !important; }
        .pull-left { float: left !important; }
    </style>
    <style>
        /* --- SIDEBAR NAVIGATION FIX (AdminLTE 4 Standard) --- */
        .sidebar-wrapper { background: #343a40 !important; }
        .nav-sidebar .nav-item { width: 100%; margin-bottom: 2px; }
        .nav-sidebar .nav-link { 
            color: #ffffff !important; 
            padding: 10px 15px !important; 
            display: flex !important; 
            align-items: center !important; 
            border-radius: 4px;
            transition: all 0.2s ease-in-out;
            font-size: 16px;
        }
        .nav-sidebar .nav-link i { 
            font-size: 16px; 
            width: 24px; 
            text-align: center; 
            margin-right: 12px;
            opacity: 1;
        }
        .nav-sidebar .nav-link p { margin-bottom: 0; line-height: 1.5; }
        .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.05) !important; color: #fff !important; }
        .nav-sidebar .nav-link.active { background-color: rgba(13, 110, 253, 0.2) !important; color: #3b82f6 !important; font-weight: 600; }
        
        /* Sub-menu (Treeview) styling */
        .nav-treeview { padding-left: 10px; background: rgba(0,0,0,0.1); }
        .nav-treeview .nav-link { padding: 8px 15px !important; font-size: 15px; }
        .nav-treeview .nav-link i { font-size: 10px; width: 20px; }
        
        /* Nav Header Styling */
        .nav-header { 
            padding: 18px 15px 8px !important; 
            font-size: 13px !important; 
            color: #6c757d !important; 
            text-transform: uppercase; 
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        /* Animation for arrows */
        .nav-item .nav-arrow { transition: transform 0.3s ease; }
        .nav-item.menu-open > .nav-link .nav-arrow { transform: rotate(-90deg); }
    </style>
    <style>
        /* --- GLOBAL THEME OVERRIDES --- */
        
        /* MODERN GLASS THEME */
        body.theme-modern .wrapper { background-color: #f8fafc; font-family: 'Outfit', sans-serif !important; }
        body.theme-modern .main-header .navbar { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.3); }
        body.theme-modern .main-sidebar { background-color: #ffffff !important; card-shadow: 10px 0 30px rgba(0,0,0,0.02) !important; border-right: 1px solid #f1f5f9; }
        body.theme-modern .main-sidebar .logo { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%) !important; color: #fff !important; }
        body.theme-modern .sidebar-menu > li > a { color: #475569 !important; border-radius: 12px; margin: 2px 10px; transition: all 0.3s ease; }
        body.theme-modern .sidebar-menu > li:hover > a, body.theme-modern .sidebar-menu > li.active > a { background: #f1f5f9 !important; color: #6366f1 !important; }
        body.theme-modern .sidebar-menu > li.header { background: transparent !important; color: #94a3b8 !important; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; font-size: 10px; padding-left: 20px; }
        body.theme-modern .content-wrapper { background-color: #f8fafc !important; }
        body.theme-modern .card { border-radius: 20px !important; border: none !important; card-shadow: 0 10px 30px rgba(0,0,0,0.03) !important; overflow: hidden; }
        body.theme-modern .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%) !important; border: none !important; border-radius: 10px !important; padding: 10px 25px !important; font-weight: 600 !important; card-shadow: 0 10px 20px rgba(99, 102, 241, 0.2) !important; }
        body.theme-modern .user-panel { border-bottom: 1px solid #f1f5f9 !important; padding: 20px 15px !important; }

        /* WORDPRESS STYLE THEME */
        body.theme-wordpress .wrapper { background-color: #f0f0f1; }
        body.theme-wordpress .main-header .navbar { background-color: #ffffff !important; border-bottom: 1px solid #dcdcde !important; }
        body.theme-wordpress .main-sidebar { background-color: #1d2327 !important; }
        body.theme-wordpress .main-sidebar .logo { background-color: #1d2327 !important; border-bottom: 1px solid #3c434a !important; }
        body.theme-wordpress .sidebar-menu > li > a { color: #f0f0f1 !important; font-size: 14px !important; }
        body.theme-wordpress .sidebar-menu > li:hover > a { background-color: #2271b1 !important; }
        body.theme-wordpress .sidebar-menu > li.active > a { background-color: #2271b1 !important; color: #fff !important; }
        body.theme-wordpress .content-wrapper { background-color: #f0f0f1 !important; }
        body.theme-wordpress .card { border-radius: 0 !important; border: 1px solid #c3c4c7 !important; card-shadow: none !important; }
        body.theme-wordpress .card-header { border-bottom: 1px solid #f0f0f1 !important; }
        body.theme-wordpress .btn-primary { background: #2271b1 !important; border-color: #2271b1 !important; border-radius: 3px !important; }
        
        /* Modal Force Visibility */
        .modal { z-index: 99999 !important; }
        .modal.in, .modal.show { display: block !important; opacity: 1 !important; background: rgba(0,0,0,0.5) !important; }
        .modal-dialog { z-index: 100000 !important; }
        .modal-content { background-color: #ffffff !important; border-radius: 12px !important; card-shadow: 0 15px 50px rgba(0,0,0,0.3) !important; border: none !important; opacity: 1 !important; visibility: visible !important; }
        .modal-body { max-height: 80vh; overflow-y: auto; }
    </style>
    <?php endif; ?>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary theme-<?= $adminTheme; ?>">
<div class="app-wrapper">
    <?php if (authCheck()): ?>
    <nav class="app-header navbar navbar-expand bg-body shadow-sm">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar-toggle" href="#" role="button"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a class="btn btn-sm btn-outline-danger ms-2" target="_blank" href="<?= base_url(); ?>"><i class="fa fa-eye"></i> <?= trans("view_site"); ?></a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-globe"></i>&nbsp;<?= isset($activeLang) ? esc($activeLang->name) : ''; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <?php if (!empty($activeLanguages)):
                            foreach ($activeLanguages as $language): ?>
                                <li>
                                    <form action="<?= base_url('Admin/setActiveLanguagePost'); ?>" method="post">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="back_url" value="<?= currentFullURL(); ?>">
                                        <button type="submit" value="<?= $language->id; ?>" name="lang_id" class="dropdown-item"><?= characterLimiter($language->name, 20, '...'); ?></button>
                                    </form>
                                </li>
                            <?php endforeach;
                        endif; ?>
                    </ul>
                </li>
                
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="<?= getUserAvatar(user()->avatar); ?>" class="user-image rounded-circle shadow-sm" alt="User Image">
                        <span class="d-none d-md-inline"><?= esc(user()->username); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
                        <li class="user-header bg-primary text-white text-center p-3">
                            <img src="<?= getUserAvatar(user()->avatar); ?>" class="rounded-circle shadow" alt="User Image" style="width: 80px; height: 80px;">
                            <p class="mt-2 mb-0"><?= esc(user()->username); ?></p>
                        </li>
                        <li class="user-footer p-2 text-end">
                            <div class="d-flex justify-content-between">
                                <a href="<?= generateProfileURL(user()->slug); ?>" class="btn btn-default btn-sm border"><?= trans("profile"); ?></a>
                                <a href="<?= generateURL('logout'); ?>" class="btn btn-danger btn-sm shadow-sm"><?= trans("logout"); ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php if (authCheck()): ?>
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="<?= adminUrl(); ?>" class="brand-link texto-center">
                <span class="brand-text fw-light"><b><?= isset($baseSettings) ? esc($baseSettings->application_name) : ''; ?></b> <?= trans("panel"); ?></span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-header"><?= trans("main_navigation"); ?></li>
                    <li class="nav-item nav-home">
                        <a class="nav-link" href="<?= adminUrl(); ?>"><i class="fa fa-home"></i><p><?= trans("home"); ?></p></a>
                    </li>
                    
                    <?php if (isSuperAdmin()): ?>
                        <li class="nav-item nav-sites">
                            <a class="nav-link" href="<?= adminUrl('sites'); ?>"><i class="fa fa-globe"></i><p>Multi Site Manager</p></a>
                        </li>
                    <?php endif; ?>
                    <?php if (hasPermission('navigation')): ?>
                        <li class="nav-item nav-navigation">
                            <a class="nav-link" href="<?= adminUrl('navigation?lang=' . $activeLang->id); ?>"><i class="fa fa-th"></i><p><?= trans("navigation"); ?></p></a>
                        </li>
                    <?php endif;
                    if (isSuperAdmin()): ?>
                        <li class="nav-item nav-themes">
                            <a class="nav-link" href="<?= adminUrl('themes'); ?>"><i class="fa fa-leaf"></i><p><?= trans("themes"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('pages')): ?>
                        <li class="nav-item nav-pages">
                            <a class="nav-link" href="<?= adminUrl('pages'); ?>"><i class="fa fa-file-text"></i><p><?= trans("pages"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('add_post')): ?>
                        <li class="nav-item nav-post-format nav-add-post">
                            <a class="nav-link" href="<?= adminUrl('post-format'); ?>"><i class="fa fa-file"></i><p><?= trans("add_post"); ?></p></a>
                        </li>

                        <li class="nav-item nav-tb-news">
                            <a class="nav-link" href="<?= adminUrl('tb-news'); ?>">
                                <i class="fa fa-rocket"></i><p>Smart TBNews</p>
                            </a>
                        </li>

                        <li class="nav-item has-treeview<?php isAdminNavActive(['smart-report'], 'menu-open'); ?> nav-smart-report">
                            <a class="nav-link" href="#">
                                <i class="fa fa-folder-open"></i> <p>Smart Report <i class="nav-arrow fa fa-angle-left float-end"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/medsos'); ?>">
                                        <i class="fa fa-share-alt"></i><p>Laporan Medsos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/policetube'); ?>">
                                        <i class="fa fa-youtube-play"></i><p>Laporan Policetube</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/youtube'); ?>">
                                        <i class="fa fa-youtube-square"></i><p>Laporan Youtube</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['skm'], 'menu-open'); ?>">
                            <a class="nav-link" href="#">
                                <i class="fa fa-line-chart"></i> <p>SKM (Survei) <i class="nav-arrow fa fa-angle-left float-end"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-skm"><a class="nav-link" href="<?= adminUrl('skm'); ?>"><i class="fa fa-list"></i><p>Daftar Survei</p></a></li>
                                <li class="nav-item nav-skm-statistics"><a class="nav-link" href="<?= adminUrl('skm/statistics'); ?>"><i class="fa fa-bar-chart"></i><p>Statistik SKM</p></a></li>
                            </ul>
                        </li>
                        <?php if (isSuperAdmin() || $generalSettings->bulk_post_upload_for_authors == 1): ?>
                            <li class="nav-item nav-import-posts">
                                <a class="nav-link" href="<?= adminUrl('bulk-post-upload'); ?>"><i class="fa fa-cloud-upload"></i><p><?= trans("bulk_post_upload"); ?></p></a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['posts', 'slider-posts', 'featured-posts', 'breaking-news', 'recommended-posts', 'pending-posts', 'scheduled-posts', 'drafts', 'update-post'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-bars"></i> <p><?= trans("posts"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-posts"><a class="nav-link" href="<?= adminUrl('posts'); ?>"><i class="fa fa-file-text"></i><p><?= trans("posts"); ?></p></a></li>
                                <?php if (hasPermission('manage_all_posts')): ?>
                                    <li class="nav-item nav-slider-posts"><a class="nav-link" href="<?= adminUrl('slider-posts'); ?>"><i class="fa fa-sliders"></i><p><?= trans("slider_posts"); ?></p></a></li>
                                    <li class="nav-item nav-featured-posts"><a class="nav-link" href="<?= adminUrl('featured-posts'); ?>"><i class="fa fa-star"></i><p><?= trans("featured_posts"); ?></p></a></li>
                                    <li class="nav-item nav-breaking-news"><a class="nav-link" href="<?= adminUrl('breaking-news'); ?>"><i class="fa fa-bolt"></i><p><?= trans("breaking_news"); ?></p></a></li>
                                    <li class="nav-item nav-recommended-posts"><a class="nav-link" href="<?= adminUrl('recommended-posts'); ?>"><i class="fa fa-thumbs-up"></i><p><?= trans("recommended_posts"); ?></p></a></li>
                                <?php endif; ?>
                                <li class="nav-item nav-pending-posts"><a class="nav-link" href="<?= adminUrl('pending-posts'); ?>"><i class="fa fa-hourglass"></i><p><?= trans("pending_posts"); ?></p></a></li>
                                <li class="nav-item nav-scheduled-posts"><a class="nav-link" href="<?= adminUrl('scheduled-posts'); ?>"><i class="fa fa-calendar"></i><p><?= trans("scheduled_posts"); ?></p></a></li>
                                <li class="nav-item nav-drafts"><a class="nav-link" href="<?= adminUrl('drafts'); ?>"><i class="fa fa-pencil-square"></i><p><?= trans("drafts"); ?></p></a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['language-settings', 'update-language', 'translations'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-language"></i> <p><?= trans("language_settings"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-language-settings"><a class="nav-link" href="<?= adminUrl('language-settings'); ?>"><i class="fa fa-globe"></i><p><?= trans("language_settings"); ?></p></a></li>
                                <li class="nav-item nav-translations"><a class="nav-link" href="<?= adminUrl('translations'); ?>"><i class="fa fa-exchange"></i><p><?= trans("translations"); ?></p></a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['font-settings', 'update-font'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-font"></i> <p><?= trans("font_settings"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-font-settings"><a class="nav-link" href="<?= adminUrl('font-settings'); ?>"><i class="fa fa-pencil"></i><p><?= trans("font_settings"); ?></p></a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['cache-system', 'cron-settings', 'ad-spaces', 'seo-tools', 'maintenance-mode'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-wrench"></i> <p>Maintenance <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-cache-system"><a class="nav-link" href="<?= adminUrl('cache-system'); ?>"><i class="fa fa-flash"></i><p><?= trans("cache_system"); ?></p></a></li>
                                <li class="nav-item nav-cron-settings"><a class="nav-link" href="<?= adminUrl('cron-settings'); ?>"><i class="fa fa-clock-o"></i><p><?= trans("cron_settings"); ?></p></a></li>
                                <li class="nav-item nav-maintenance-mode"><a class="nav-link" href="<?= adminUrl('maintenance-mode'); ?>"><i class="fa fa-exclamation-triangle"></i><p><?= trans("maintenance_mode"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('rss_feeds')): ?>
                        <li class="nav-item nav-feeds"><a class="nav-link" href="<?= adminUrl('feeds'); ?>"><i class="fa fa-rss" aria-hidden="true"></i><p><?= trans("rss_feeds"); ?></p></a></li>
                    <?php endif; 
                    
                    if (isSuperAdmin()): ?>
                        <li class="nav-item nav-import-wp">
                            <a class="nav-link" href="<?= adminUrl('import-wp'); ?>"><i class="fa fa-wordpress"></i><p>Import WordPress</p></a>
                        </li>
                    <?php endif; 
                    
                    if (hasPermission('categories')): ?>
                        <li class="nav-item nav-categories"><a class="nav-link" href="<?= adminUrl('categories'); ?>"><i class="fa fa-folder-open" aria-hidden="true"></i><p><?= trans("categories"); ?></p></a></li>
                    <?php endif;
                    if (hasPermission('widgets')): ?>
                        <li class="nav-item nav-widgets"><a class="nav-link" href="<?= adminUrl('widgets'); ?>"><i class="fa fa-th" aria-hidden="true"></i><p><?= trans("widgets"); ?></p></a></li>
                    <?php endif;
                    if (hasPermission('polls')): ?>
                        <li class="nav-item nav-polls"><a class="nav-link" href="<?= adminUrl('polls'); ?>"><i class="fa fa-list" aria-hidden="true"></i><p><?= trans("polls"); ?></p></a></li>
                    <?php endif;
                    if (hasPermission('gallery')): ?>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['gallery-images', 'gallery-albums', 'gallery-categories', 'update-gallery-image', 'update-gallery-album', 'update-gallery-category', 'gallery-add-image'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-image"></i> <p><?= trans("gallery"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-gallery-images"><a class="nav-link" href="<?= adminUrl('gallery-images'); ?>"><i class="fa fa-camera"></i><p><?= trans("images"); ?></p></a></li>
                                <li class="nav-item nav-gallery-albums"><a class="nav-link" href="<?= adminUrl('gallery-albums'); ?>"><i class="fa fa-book"></i><p><?= trans("albums"); ?></p></a></li>
                                <li class="nav-item nav-gallery-categories"><a class="nav-link" href="<?= adminUrl('gallery-categories'); ?>"><i class="fa fa-folder"></i><p><?= trans("categories"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('comments_contact')): ?>
                        <li class="nav-item nav-contact-messages">
                            <a class="nav-link" href="<?= adminUrl('contact-messages'); ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i><p><?= trans("contact_messages"); ?></p></a>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['comments', 'pending-comments'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-comments"></i> <p><?= trans("comments"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-pending-comments"><a class="nav-link" href="<?= adminUrl('pending-comments'); ?>"><i class="fa fa-clock-o"></i><p><?= trans("pending_comments"); ?></p></a></li>
                                <li class="nav-item nav-comments"><a class="nav-link" href="<?= adminUrl('comments'); ?>"><i class="fa fa-check-circle"></i><p><?= trans("approved_comments"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('newsletter')): ?>
                        <li class="nav-item nav-newsletter">
                            <a class="nav-link" href="<?= adminUrl('newsletter'); ?>"><i class="fa fa-envelope" aria-hidden="true"></i><p><?= trans("newsletter"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('reward_system')): ?>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['reward-system'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-money"></i> <p><?= trans("reward_system"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-reward-system"><a class="nav-link" href="<?= adminUrl('reward-system'); ?>"><i class="fa fa-trophy"></i><p><?= trans("reward_system"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-earnings"><a class="nav-link" href="<?= adminUrl('reward-system/earnings'); ?>"><i class="fa fa-usd"></i><p><?= trans("earnings"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-payouts"><a class="nav-link" href="<?= adminUrl('reward-system/payouts'); ?>"><i class="fa fa-credit-card"></i><p><?= trans("payouts"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-pageviews"><a class="nav-link" href="<?= adminUrl('reward-system/pageviews'); ?>"><i class="fa fa-line-chart"></i><p><?= trans("pageviews"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('ad_spaces')): ?>
                        <li class="nav-item nav-ad-spaces">
                            <a class="nav-link" href="<?= adminUrl('ad-spaces'); ?>"><i class="fa fa-dollar" aria-hidden="true"></i><p><?= trans("ad_spaces"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('users')): ?>
                        <li class="nav-item nav-users">
                            <a class="nav-link" href="<?= adminUrl('users'); ?>"><i class="fa fa-users" aria-hidden="true"></i><p><?= trans("users"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('roles_permissions')): ?>
                        <li class="nav-item nav-roles-permissions">
                            <a class="nav-link" href="<?= adminUrl('roles-permissions'); ?>"><i class="fa fa-key" aria-hidden="true"></i><p><?= trans("roles_permissions"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('seo_tools')): ?>
                        <li class="nav-item nav-seo-tools">
                            <a class="nav-link" href="<?= adminUrl('seo-tools'); ?>"><i class="fa fa-wrench"></i><p><?= trans("seo_tools"); ?></p></a>
                        </li>
                    <?php endif;
                    if (isSuperAdmin()): ?>
                        <li class="nav-item nav-storage">
                            <a class="nav-link" href="<?= adminUrl('storage'); ?>"><i class="fa fa-cloud-upload"></i><p><?= trans("storage"); ?></p></a>
                        </li>
                        <li class="nav-item nav-cache-system">
                            <a class="nav-link" href="<?= adminUrl('cache-system'); ?>"><i class="fa fa-database"></i><p><?= trans("cache_system"); ?></p></a>
                        </li>
                        <li class="nav-item nav-google-news">
                            <a class="nav-link" href="<?= adminUrl('google-news'); ?>"><i class="fa fa-newspaper-o"></i><p><?= trans("google_news"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('settings')): ?>
                        <li class="nav-item nav-preferences">
                            <a class="nav-link" href="<?= adminUrl('preferences'); ?>"><i class="fa fa-check-square-o"></i><p><?= trans("preferences"); ?></p></a>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['settings', 'general-settings', 'email-settings', 'social-media-settings', 'facebook-login', 'google-login', 'storage-settings', 'route-settings', 'visual-settings', 'header-settings', 'mega-menu-settings', 'dashboard-themes', 'font-settings', 'social-login-settings'], 'menu-open'); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-cogs"></i> <p><?= trans("settings"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-general-settings"><a class="nav-link" href="<?= adminUrl('general-settings'); ?>"><i class="fa fa-cog"></i><p><?= trans("general_settings"); ?></p></a></li>
                                <li class="nav-item nav-visual-settings"><a class="nav-link" href="<?= adminUrl('visual-settings'); ?>"><i class="fa fa-paint-brush"></i><p><?= trans("visual_settings"); ?></p></a></li>
                                <li class="nav-item nav-header-settings"><a class="nav-link" href="<?= adminUrl('header-settings'); ?>"><i class="fa fa-television"></i> <p>Header Settings</p></a></li>
                                <li class="nav-item nav-mega-menu-settings"><a class="nav-link" href="<?= adminUrl('mega-menu-settings'); ?>"><i class="fa fa-list-alt"></i> <p>Mega Menu Custom</p></a></li>
                                <li class="nav-item nav-dashboard-themes"><a class="nav-link" href="<?= adminUrl('dashboard-themes'); ?>"><i class="fa fa-magic"></i> <p>Tema Dashboard</p></a></li>
                                <li class="nav-item nav-font-settings"><a class="nav-link" href="<?= adminUrl('font-settings'); ?>"><i class="fa fa-font"></i> <p><?= trans("font_settings"); ?></p></a></li>
                                <li class="nav-item nav-email-settings"><a class="nav-link" href="<?= adminUrl('email-settings'); ?>"><i class="fa fa-envelope"></i><p><?= trans("email_settings"); ?></p></a></li>
                                <li class="nav-item nav-social-media-settings"><a class="nav-link" href="<?= adminUrl('social-media-settings'); ?>"><i class="fa fa-share-alt"></i><p><?= trans("social_media_settings"); ?></p></a></li>
                                <li class="nav-item nav-facebook-login nav-google-login nav-social-login-settings"><a class="nav-link" href="<?= adminUrl('facebook-login'); ?>"><i class="fa fa-key"></i><p>Social Login Settings</p></a></li>
                                <li class="nav-item nav-storage-settings"><a class="nav-link" href="<?= adminUrl('storage-settings'); ?>"><i class="fa fa-database"></i><p><?= trans("storage_settings"); ?></p></a></li>
                                <li class="nav-item nav-route-settings"><a class="nav-link" href="<?= adminUrl('route-settings'); ?>"><i class="fa fa-map-signs"></i><p><?= trans("route_settings"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if ($generalSettings->reward_system_status == 1 && user()->reward_system_enabled == 1): ?>
                        <li class="nav-author-earnings"><a href="<?= adminUrl('author-earnings'); ?>"><i class="fa fa-money" aria-hidden="true"></i><span><?= trans("my_earnings"); ?></span></a></li>
                    <?php endif; ?>
                        <li class="nav-item">
                            <div class="database-backup px-3 py-2">
                                <form action="<?= base_url('Admin/downloadDatabaseBackup'); ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-dark btn-sm w-100"><i class="fa fa-download"></i>&nbsp;&nbsp;<?= trans("download_database_backup"); ?></button>
                                </form>
                            </div>
                        </li>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <?php endif; ?>
    <?php
    $segment2 = $segment = getSegmentValue(2);
    $segment3 = $segment = getSegmentValue(3);
    $uriString = $segment2;
    if (!empty($segment3)) {
        $uriString .= '-' . $segment3;
    } ?>
    <style>
        <?php if(!empty($uriString)):
        echo '.nav-'.$uriString.' > a{color: #fff !important;}';
        else:
        echo '.nav-home > a{color: #fff !important;}';
        endif;?>
        #table_dropdown select{padding: 0 12px;}
    </style>
    <main class="app-main">
        <div class="content-wrapper">
            <section class="content" style="min-height: 1180px;">
            <div class="row">
                <div class="col-sm-12">
                    <?= view('admin/includes/_messages'); ?>
                </div>
            </div>

