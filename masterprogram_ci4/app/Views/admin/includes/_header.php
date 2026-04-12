<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title); ?> - <?= trans("admin"); ?>&nbsp;<?= esc($baseSettings->site_title); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= csrf_meta(); ?>
    <link rel="shortcut icon" type="image/png" href="<?= getFavicon(); ?>"/>
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
        .app-main { flex: 1; padding: 20px; background: #f4f6f9; min-height: calc(100vh - 120px); }
        .content-wrapper { background: transparent !important; margin-left: 0 !important; padding: 0 !important; min-height: auto !important; }
        .row { display: flex; flex-wrap: wrap; margin-right: -7.5px; margin-left: -7.5px; }
        .col-sm-12, .col-md-12, .col-lg-12 { width: 100%; padding: 0 7.5px; }
        .float-right-container { float: right; }
        .treeview-menu { list-style: none; padding-left: 20px; display: none; }
        .menu-open > .treeview-menu { display: block; }
        .sidebar-menu li a { display: flex; align-items: center; padding: 10px 15px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu li a i { width: 30px; }
        
        /* Modern Scrollbar */
        .sidebar-wrapper { overflow-y: auto; height: calc(100vh - 60px); }
        .sidebar-wrapper::-webkit-scrollbar { width: 6px; }
        .sidebar-wrapper::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* Legacy AdminLTE 2 classes support */
        .box { position: relative; border-radius: 8px; background: #ffffff; border-top: 3px solid #d2d6de; margin-bottom: 20px; width: 100%; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
        .box-header { color: #444; display: block; padding: 10px; position: relative; border-bottom: 1px solid #f4f4f4; }
        .box-body { border-radius: 0 0 3px 3px; padding: 10px; }
        .pull-right { float: right !important; }
        .pull-left { float: left !important; }
    </style>
    <style>
        /* Sidebar Item Fix */
        .nav-sidebar .nav-item { width: 100%; }
        .nav-sidebar .nav-link { color: #c2c7d0 !important; display: flex !important; align-items: center !important; gap: 10px; }
        .nav-sidebar .nav-link i { font-size: 16px; width: 20px; text-align: center; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background-color: rgba(255,255,255,.1) !important; color: #fff !important; }
        .nav-treeview { padding-left: 15px; background: rgba(255,255,255,.02); }
        .nav-header { padding: 15px 20px 5px !important; font-size: 12px; color: #6c757d; text-transform: uppercase; }
    </style>

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
                        <i class="fa fa-globe"></i>&nbsp;<?= esc($activeLang->name); ?>
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
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="<?= adminUrl(); ?>" class="brand-link texto-center">
                <span class="brand-text fw-light"><b><?= esc($baseSettings->application_name); ?></b> <?= trans("panel"); ?></span>
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

                        <li class="nav-item has-treeview nav-smart-report">
                            <a class="nav-link" href="#">
                                <i class="fa fa-folder-open"></i> <p>Smart Report <i class="nav-arrow fa fa-angle-left float-end"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/medsos'); ?>">
                                        <i class="fa fa-circle-o"></i><p>Laporan Medsos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/policetube'); ?>">
                                        <i class="fa fa-circle-o"></i><p>Laporan Policetube</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= adminUrl('smart-report/youtube'); ?>">
                                        <i class="fa fa-circle-o"></i><p>Laporan Youtube</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['skm']); ?>">
                            <a class="nav-link" href="#">
                                <i class="fa fa-line-chart"></i> <p>SKM (Survei) <i class="nav-arrow fa fa-angle-left float-end"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-skm"><a class="nav-link" href="<?= adminUrl('skm'); ?>"><i class="fa fa-circle-o"></i><p>Daftar Survei</p></a></li>
                                <li class="nav-item nav-skm-statistics"><a class="nav-link" href="<?= adminUrl('skm/statistics'); ?>"><i class="fa fa-circle-o"></i><p>Statistik SKM</p></a></li>
                            </ul>
                        </li>
                        <?php if (isSuperAdmin() || $generalSettings->bulk_post_upload_for_authors == 1): ?>
                            <li class="nav-item nav-import-posts">
                                <a class="nav-link" href="<?= adminUrl('bulk-post-upload'); ?>"><i class="fa fa-cloud-upload"></i><p><?= trans("bulk_post_upload"); ?></p></a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['posts', 'slider-posts', 'featured-posts', 'breaking-news', 'recommended-posts', 'pending-posts', 'scheduled-posts', 'drafts', 'update-post']); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-bars"></i> <p><?= trans("posts"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-posts"><a class="nav-link" href="<?= adminUrl('posts'); ?>"><p><?= trans("posts"); ?></p></a></li>
                                <?php if (hasPermission('manage_all_posts')): ?>
                                    <li class="nav-item nav-slider-posts"><a class="nav-link" href="<?= adminUrl('slider-posts'); ?>"><p><?= trans("slider_posts"); ?></p></a></li>
                                    <li class="nav-item nav-featured-posts"><a class="nav-link" href="<?= adminUrl('featured-posts'); ?>"><p><?= trans("featured_posts"); ?></p></a></li>
                                    <li class="nav-item nav-breaking-news"><a class="nav-link" href="<?= adminUrl('breaking-news'); ?>"><p><?= trans("breaking_news"); ?></p></a></li>
                                    <li class="nav-item nav-recommended-posts"><a class="nav-link" href="<?= adminUrl('recommended-posts'); ?>"><p><?= trans("recommended_posts"); ?></p></a></li>
                                <?php endif; ?>
                                <li class="nav-item nav-pending-posts"><a class="nav-link" href="<?= adminUrl('pending-posts'); ?>"><p><?= trans("pending_posts"); ?></p></a></li>
                                <li class="nav-item nav-scheduled-posts"><a class="nav-link" href="<?= adminUrl('scheduled-posts'); ?>"><p><?= trans("scheduled_posts"); ?></p></a></li>
                                <li class="nav-item nav-drafts"><a class="nav-link" href="<?= adminUrl('drafts'); ?>"><p><?= trans("drafts"); ?></p></a></li>
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
                        <li class="nav-item has-treeview<?php isAdminNavActive(['gallery-images', 'gallery-albums', 'gallery-categories', 'update-gallery-image', 'update-gallery-album', 'update-gallery-category', 'gallery-add-image']); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-image"></i> <p><?= trans("gallery"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-gallery-images"><a class="nav-link" href="<?= adminUrl('gallery-images'); ?>"><p><?= trans("images"); ?></p></a></li>
                                <li class="nav-item nav-gallery-albums"><a class="nav-link" href="<?= adminUrl('gallery-albums'); ?>"><p><?= trans("albums"); ?></p></a></li>
                                <li class="nav-item nav-gallery-categories"><a class="nav-link" href="<?= adminUrl('gallery-categories'); ?>"><p><?= trans("categories"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('comments_contact')): ?>
                        <li class="nav-item nav-contact-messages">
                            <a class="nav-link" href="<?= adminUrl('contact-messages'); ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i><p><?= trans("contact_messages"); ?></p></a>
                        </li>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['comments', 'pending-comments']); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-comments"></i> <p><?= trans("comments"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-pending-comments"><a class="nav-link" href="<?= adminUrl('pending-comments'); ?>"><p><?= trans("pending_comments"); ?></p></a></li>
                                <li class="nav-item nav-comments"><a class="nav-link" href="<?= adminUrl('comments'); ?>"><p><?= trans("approved_comments"); ?></p></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('newsletter')): ?>
                        <li class="nav-item nav-newsletter">
                            <a class="nav-link" href="<?= adminUrl('newsletter'); ?>"><i class="fa fa-envelope" aria-hidden="true"></i><p><?= trans("newsletter"); ?></p></a>
                        </li>
                    <?php endif;
                    if (hasPermission('reward_system')): ?>
                        <li class="nav-item has-treeview<?php isAdminNavActive(['reward-system']); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-money"></i> <p><?= trans("reward_system"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-reward-system"><a class="nav-link" href="<?= adminUrl('reward-system'); ?>"><p><?= trans("reward_system"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-earnings"><a class="nav-link" href="<?= adminUrl('reward-system/earnings'); ?>"><p><?= trans("earnings"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-payouts"><a class="nav-link" href="<?= adminUrl('reward-system/payouts'); ?>"><p><?= trans("payouts"); ?></p></a></li>
                                <li class="nav-item nav-reward-system-pageviews"><a class="nav-link" href="<?= adminUrl('reward-system/pageviews'); ?>"><p><?= trans("pageviews"); ?></p></a></li>
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
                        <li class="nav-item has-treeview<?php isAdminNavActive(['general-settings', 'language-settings', 'email-settings', 'font-settings', 'social-login-settings', 'route-settings', 'header-settings', 'mega-menu-settings']); ?>">
                            <a class="nav-link" href="#"><i class="fa fa-cogs"></i><p><?= trans("settings"); ?> <i class="nav-arrow fa fa-angle-left float-end"></i></p></a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item nav-general-settings">
                                    <a class="nav-link" href="<?= adminUrl('general-settings'); ?>"><i class="fa fa-sliders"></i> <p><?= trans("general_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-header-settings">
                                    <a class="nav-link" href="<?= adminUrl('header-settings'); ?>"><i class="fa fa-paint-brush"></i> <p>Header Settings</p></a>
                                </li>
                                <li class="nav-item nav-mega-menu-settings">
                                    <a class="nav-link" href="<?= adminUrl('mega-menu-settings'); ?>"><i class="fa fa-window-restore"></i> <p>Mega Menu Custom</p></a>
                                </li>
                                <li class="nav-item nav-language-settings">
                                    <a class="nav-link" href="<?= adminUrl('language-settings'); ?>"><i class="fa fa-language"></i> <p><?= trans("language_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-email-settings">
                                    <a class="nav-link" href="<?= adminUrl('email-settings'); ?>"><i class="fa fa-envelope-o"></i> <p><?= trans("email_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-font-settings">
                                    <a class="nav-link" href="<?= adminUrl('font-settings'); ?>"><i class="fa fa-font"></i> <p><?= trans("font_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-social-login-settings">
                                    <a class="nav-link" href="<?= adminUrl('social-login-settings'); ?>"><i class="fa fa-share-alt"></i> <p><?= trans("social_login_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-route-settings">
                                    <a class="nav-link" href="<?= adminUrl('route-settings'); ?>"><i class="fa fa-map-signs"></i> <p><?= trans("route_settings"); ?></p></a>
                                </li>
                                <li class="nav-item nav-dashboard-themes">
                                    <a class="nav-link" href="<?= adminUrl('dashboard-themes'); ?>"><i class="fa fa-paint-brush"></i> <p>Tema Dashboard</p></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif;
                    if ($generalSettings->reward_system_status == 1 && user()->reward_system_enabled == 1): ?>
                        <li class="nav-author-earnings"><a href="<?= adminUrl('author-earnings'); ?>"><i class="fa fa-money" aria-hidden="true"></i><span><?= trans("my_earnings"); ?></span></a></li>
                    <?php endif;
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

