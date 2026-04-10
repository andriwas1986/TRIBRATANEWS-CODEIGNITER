<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title); ?> - <?= trans("admin"); ?>&nbsp;<?= esc($baseSettings->site_title); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= csrf_meta(); ?>
    <link rel="shortcut icon" type="image/png" href="<?= getFavicon(); ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/bootstrap5/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/AdminLTE-2.4.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/_all-skins.min.css'); ?>">
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
    <style>
        /* --- RESTORE ADMINLTE V2 CUSTOM HEADER (WHITE) & FIX BS5 OVERLAPS --- */
        .main-header .navbar { background-color: #ffffff !important; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-bottom: none; }
        .main-header .logo { background-color: #1e293b !important; color: #fff !important; border-bottom: 0 solid transparent; }
        
        /* Teks & Ikon Navbar (Dibuat Lebih Tipis ke Atas) */
        .main-header .navbar { min-height: 40px !important; display: flex; justify-content: space-between; align-items: center; padding: 0 !important; }
        .main-header .navbar .nav>li>a { font-size: 14px !important; color: #475569 !important; padding: 10px 15px !important; font-weight: 600; display: flex; align-items: center; gap: 6px; }
        .main-header .navbar .sidebar-toggle { color: #64748b !important; padding: 10px 15px !important; display: inline-flex; align-items: center; justify-content: center; font-size: 16px !important; height: 100%; }
        .main-header .navbar .sidebar-toggle:hover { background-color: #f8fafc !important; }
        .main-header .sidebar-toggle:before { content: ""; } /* Hindari ikon kembar */
        
        /* Tombol Lihat Website */
        .main-header .navbar .btn-site-prev { font-size: 13px !important; padding: 6px 14px !important; margin-top: 8px !important; margin-bottom: 8px; margin-right: 15px; border-radius: 20px; color: #ffffff !important; font-weight: 700 !important; background-color: #10b981 !important; border: 1px solid #059669 !important; }
        .main-header .navbar .btn-site-prev i { color: #ffffff !important; }
        
        /* Fix BS5 flex layout pada Nav Kanan (Dorong ke pojok kanan) */
        .navbar-custom-menu { margin-left: auto; display: flex; align-items: center; }
        .navbar-custom-menu > .nav { flex-direction: row; display: flex; align-items: center; margin: 0; gap: 5px; }
        
        /* Fix Layout Overlap (Ticker yang terpotong) */
        .content-wrapper { margin-top: 0px !important; }
        .content-wrapper > .content { padding-top: 30px !important; padding-left: 25px; padding-right: 25px; }
        
        /* Dropdown & Avatar Fix */
        .user-menu .dropdown-toggle { display: flex; align-items: center; }
        .user-image { float: none; width: 30px !important; height: 30px !important; margin-top: 0; margin-right: 8px; }
        
        /* Dropdown Menu Item Size */
        .dropdown-menu { box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: 1px solid #f1f5f9; border-radius: 12px; padding: 5px 0; }
        .dropdown-menu > li > a { font-size: 13px !important; padding: 6px 20px !important; color: #475569 !important; display: block; }
        .dropdown-menu > li > a > i { margin-right: 10px; color: #64748b; width: 16px; text-align: center; }
        .dropdown-menu > li > a:hover { background-color: #f8fafc !important; color: #00a8ff !important; }
        
        /* Fix Bootstrap 5 Link Underline & Font Overrides */
        a { text-decoration: none !important; }
        .main-header .logo, .main-sidebar .logo { font-size: 20px !important; font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif !important; letter-spacing: 0px; font-weight: 300 !important; color: #fff !important; }
        .main-header .logo b, .main-sidebar .logo b { font-weight: 800 !important; }
        
        /* General Font Size Normalization */
        body, .content-wrapper, .main-footer { font-size: 14px !important; }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><i class="fa fa-bars" aria-hidden="true"></i></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a class="btn btn-sm btn-danger pull-left btn-site-prev" target="_blank" href="<?= base_url(); ?>"><i class="fa fa-eye"></i> <?= trans("view_site"); ?></a></li>
                    <li class="dropdown user-menu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-globe"></i>&nbsp;
                            <?= esc($activeLang->name); ?>
                            <span class="fa fa-caret-down"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (!empty($activeLanguages)):
                                foreach ($activeLanguages as $language): ?>
                                    <li>
                                        <form action="<?= base_url('Admin/setActiveLanguagePost'); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="back_url" value="<?= currentFullURL(); ?>">
                                            <button type="submit" value="<?= $language->id; ?>" name="lang_id" class="control-panel-lang-btn"><?= characterLimiter($language->name, 20, '...'); ?></button>
                                        </form>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?= getUserAvatar(user()->avatar); ?>" class="user-image" alt="">
                            <span class="hidden-xs"><?= esc(user()->username); ?> <i class="fa fa-caret-down"></i> </span>
                        </a>
                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                            <li><a href="<?= generateProfileURL(user()->slug); ?>"><i class="fa fa-user"></i> <?= trans("profile"); ?></a></li>
                            <li><a href="<?= generateURL('settings'); ?>"><i class="fa fa-cog"></i> <?= trans("update_profile"); ?></a></li>
                            <li><a href="<?= generateURL('settings', 'change_password'); ?>"><i class="fa fa-lock"></i> <?= trans("change_password"); ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= generateURL('logout'); ?>"><i class="fa fa-sign-out"></i> <?= trans("logout"); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <a href="<?= adminUrl(); ?>" class="logo">
                <span class="logo-lg"><b><?= esc($baseSettings->application_name); ?></b> <?= trans("panel"); ?></span>
            </a>
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= getUserAvatar(user()->avatar); ?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?= esc(user()->username); ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> <?= trans("online"); ?></a>
                </div>
            </div>
            <div class="sidebar-scrollbar" style="display: block; height: 100%; min-height: 100%;">
                <ul class="sidebar-menu" data-widget="tree" style="padding-bottom: 160px;">
                    <li class="header"><?= trans("main_navigation"); ?></li>
                    <li class="nav-home">
                        <a href="<?= adminUrl(); ?>"><i class="fa fa-home"></i><span><?= trans("home"); ?></span></a>
                    </li>
                    
                    <?php if (isSuperAdmin()): ?>
                        <li class="nav-sites">
                            <a href="<?= adminUrl('sites'); ?>"><i class="fa fa-globe"></i><span>Multi Site Manager</span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (hasPermission('navigation')): ?>
                        <li class="nav-navigation">
                            <a href="<?= adminUrl('navigation?lang=' . $activeLang->id); ?>"><i class="fa fa-th"></i><span><?= trans("navigation"); ?></span></a>
                        </li>
                    <?php endif;
                    if (isSuperAdmin()): ?>
                        <li class="nav-themes">
                            <a href="<?= adminUrl('themes'); ?>"><i class="fa fa-leaf"></i><span><?= trans("themes"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('pages')): ?>
                        <li class="nav-pages">
                            <a href="<?= adminUrl('pages'); ?>"><i class="fa fa-file-text"></i><span><?= trans("pages"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('add_post')): ?>
                        <li class="nav-post-format nav-add-post">
                            <a href="<?= adminUrl('post-format'); ?>"><i class="fa fa-file"></i><span><?= trans("add_post"); ?></span></a>
                        </li>

                        <li class="nav-tb-news">
                            <a href="<?= adminUrl('tb-news'); ?>">
                                <i class="fa fa-rocket"></i><span>Smart TBNews</span>
                            </a>
                        </li>

                        <li class="treeview nav-smart-report">
                            <a href="#">
                                <i class="fa fa-folder-open"></i> <span>Smart Report</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="<?= adminUrl('smart-report/medsos'); ?>">
                                        <i class="fa fa-circle-o"></i> Laporan Medsos
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= adminUrl('smart-report/policetube'); ?>">
                                        <i class="fa fa-circle-o"></i> Laporan Policetube
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= adminUrl('smart-report/youtube'); ?>">
                                        <i class="fa fa-circle-o"></i> Laporan Youtube
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (isSuperAdmin() || $generalSettings->bulk_post_upload_for_authors == 1): ?>
                            <li class="nav-import-posts">
                                <a href="<?= adminUrl('bulk-post-upload'); ?>"><i class="fa fa-cloud-upload"></i><span><?= trans("bulk_post_upload"); ?></span></a>
                            </li>
                        <?php endif; ?>
                        <li class="treeview<?php isAdminNavActive(['posts', 'slider-posts', 'featured-posts', 'breaking-news', 'recommended-posts', 'pending-posts', 'scheduled-posts', 'drafts', 'update-post']); ?>">
                            <a href="#"><i class="fa fa-bars"></i> <span><?= trans("posts"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li class="nav-posts"><a href="<?= adminUrl('posts'); ?>"><?= trans("posts"); ?></a></li>
                                <?php if (hasPermission('manage_all_posts')): ?>
                                    <li class="nav-slider-posts"><a href="<?= adminUrl('slider-posts'); ?>"><?= trans("slider_posts"); ?></a></li>
                                    <li class="nav-featured-posts"><a href="<?= adminUrl('featured-posts'); ?>"><?= trans("featured_posts"); ?></a></li>
                                    <li class="nav-breaking-news"><a href="<?= adminUrl('breaking-news'); ?>"><?= trans("breaking_news"); ?></a></li>
                                    <li class="nav-recommended-posts"><a href="<?= adminUrl('recommended-posts'); ?>"><?= trans("recommended_posts"); ?></a></li>
                                <?php endif; ?>
                                <li class="nav-pending-posts"><a href="<?= adminUrl('pending-posts'); ?>"><?= trans("pending_posts"); ?></a></li>
                                <li class="nav-scheduled-posts"><a href="<?= adminUrl('scheduled-posts'); ?>"><?= trans("scheduled_posts"); ?></a></li>
                                <li class="nav-drafts"><a href="<?= adminUrl('drafts'); ?>"><?= trans("drafts"); ?></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('rss_feeds')): ?>
                        <li class="nav-feeds"><a href="<?= adminUrl('feeds'); ?>"><i class="fa fa-rss" aria-hidden="true"></i><span><?= trans("rss_feeds"); ?></span></a></li>
                    <?php endif; 
                    
                    if (isSuperAdmin()): ?>
                        <li class="nav-import-wp">
                            <a href="<?= adminUrl('import-wp'); ?>"><i class="fa fa-wordpress"></i><span>Import WordPress</span></a>
                        </li>
                    <?php endif; 
                    
                    if (hasPermission('categories')): ?>
                        <li class="nav-categories"><a href="<?= adminUrl('categories'); ?>"><i class="fa fa-folder-open" aria-hidden="true"></i><span><?= trans("categories"); ?></span></a></li>
                    <?php endif;
                    if (hasPermission('widgets')): ?>
                        <li class="nav-widgets"><a href="<?= adminUrl('widgets'); ?>"><i class="fa fa-th" aria-hidden="true"></i><span><?= trans("widgets"); ?></span></a></li>
                    <?php endif;
                    if (hasPermission('polls')): ?>
                        <li class="nav-polls"><a href="<?= adminUrl('polls'); ?>"><i class="fa fa-list" aria-hidden="true"></i><span><?= trans("polls"); ?></span></a></li>
                    <?php endif;
                    if (hasPermission('gallery')): ?>
                        <li class="treeview<?php isAdminNavActive(['gallery-images', 'gallery-albums', 'gallery-categories', 'update-gallery-image', 'update-gallery-album', 'update-gallery-category', 'gallery-add-image']); ?>">
                            <a href="#"><i class="fa fa-image"></i> <span><?= trans("gallery"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li class="nav-gallery-images"><a href="<?= adminUrl('gallery-images'); ?>"><?= trans("images"); ?></a></li>
                                <li class="nav-gallery-albums"><a href="<?= adminUrl('gallery-albums'); ?>"><?= trans("albums"); ?></a></li>
                                <li class="nav-gallery-categories"><a href="<?= adminUrl('gallery-categories'); ?>"><?= trans("categories"); ?></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('comments_contact')): ?>
                        <li class="nav-contact-messages">
                            <a href="<?= adminUrl('contact-messages'); ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i><span><?= trans("contact_messages"); ?></span></a>
                        </li>
                        <li class="treeview<?php isAdminNavActive(['comments', 'pending-comments']); ?>">
                            <a href="#"><i class="fa fa-comments"></i> <span><?= trans("comments"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li class="nav-pending-comments"><a href="<?= adminUrl('pending-comments'); ?>"><?= trans("pending_comments"); ?></a></li>
                                <li class="nav-comments"><a href="<?= adminUrl('comments'); ?>"><?= trans("approved_comments"); ?></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('newsletter')): ?>
                        <li class="nav-newsletter">
                            <a href="<?= adminUrl('newsletter'); ?>"><i class="fa fa-envelope" aria-hidden="true"></i><span><?= trans("newsletter"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('reward_system')): ?>
                        <li class="treeview<?php isAdminNavActive(['reward-system']); ?>">
                            <a href="#"><i class="fa fa-money"></i> <span><?= trans("reward_system"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li class="nav-reward-system"><a href="<?= adminUrl('reward-system'); ?>"><?= trans("reward_system"); ?></a></li>
                                <li class="nav-reward-system-earnings"><a href="<?= adminUrl('reward-system/earnings'); ?>"><?= trans("earnings"); ?></a></li>
                                <li class="nav-reward-system-payouts"><a href="<?= adminUrl('reward-system/payouts'); ?>"><?= trans("payouts"); ?></a></li>
                                <li class="nav-reward-system-pageviews"><a href="<?= adminUrl('reward-system/pageviews'); ?>"><?= trans("pageviews"); ?></a></li>
                            </ul>
                        </li>
                    <?php endif;
                    if (hasPermission('ad_spaces')): ?>
                        <li class="nav-ad-spaces">
                            <a href="<?= adminUrl('ad-spaces'); ?>"><i class="fa fa-dollar" aria-hidden="true"></i><span><?= trans("ad_spaces"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('users')): ?>
                        <li class="nav-users">
                            <a href="<?= adminUrl('users'); ?>"><i class="fa fa-users" aria-hidden="true"></i><span><?= trans("users"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('roles_permissions')): ?>
                        <li class="nav-roles-permissions">
                            <a href="<?= adminUrl('roles-permissions'); ?>"><i class="fa fa-key" aria-hidden="true"></i><span><?= trans("roles_permissions"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('seo_tools')): ?>
                        <li class="nav-seo-tools">
                            <a href="<?= adminUrl('seo-tools'); ?>"><i class="fa fa-wrench"></i><span><?= trans("seo_tools"); ?></span></a>
                        </li>
                    <?php endif;
                    if (isSuperAdmin()): ?>
                        <li class="nav-storage">
                            <a href="<?= adminUrl('storage'); ?>"><i class="fa fa-cloud-upload"></i><span><?= trans("storage"); ?></span></a>
                        </li>
                        <li class="nav-cache-system">
                            <a href="<?= adminUrl('cache-system'); ?>"><i class="fa fa-database"></i><span><?= trans("cache_system"); ?></span></a>
                        </li>
                        <li class="nav-google-news">
                            <a href="<?= adminUrl('google-news'); ?>"><i class="fa fa-newspaper-o"></i><span><?= trans("google_news"); ?></span></a>
                        </li>
                    <?php endif;
                    if (hasPermission('settings')): ?>
                        <li class="nav-preferences">
                            <a href="<?= adminUrl('preferences'); ?>"><i class="fa fa-check-square-o"></i><span><?= trans("preferences"); ?></span></a>
                        </li>
                        <li class="treeview<?php isAdminNavActive(['general-settings', 'language-settings', 'email-settings', 'font-settings', 'social-login-settings', 'route-settings', 'header-settings', 'mega-menu-settings']); ?>">
                            <a href="#"><i class="fa fa-cogs"></i><span><?= trans("settings"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li class="nav-general-settings">
                                    <a href="<?= adminUrl('general-settings'); ?>"><span><?= trans("general_settings"); ?></span></a>
                                </li>
                                <li class="nav-header-settings">
                                    <a href="<?= adminUrl('header-settings'); ?>"><i class="fa fa-paint-brush"></i> <span>Header Settings</span></a>
                                </li>
                                <li class="nav-mega-menu-settings">
                                    <a href="<?= adminUrl('mega-menu-settings'); ?>"><i class="fa fa-window-restore"></i> <span>Mega Menu Custom</span></a>
                                </li>
                                <li class="nav-language-settings">
                                    <a href="<?= adminUrl('language-settings'); ?>"><span><?= trans("language_settings"); ?></span></a>
                                </li>
                                <li class="nav-email-settings">
                                    <a href="<?= adminUrl('email-settings'); ?>"><span><?= trans("email_settings"); ?></span></a>
                                </li>
                                <li class="nav-font-settings">
                                    <a href="<?= adminUrl('font-settings'); ?>"><span><?= trans("font_settings"); ?></span></a>
                                </li>
                                <li class="nav-social-login-settings">
                                    <a href="<?= adminUrl('social-login-settings'); ?>"><span><?= trans("social_login_settings"); ?></span></a>
                                </li>
                                <li class="nav-route-settings">
                                    <a href="<?= adminUrl('route-settings'); ?>"><span><?= trans("route_settings"); ?></span></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif;
                    if ($generalSettings->reward_system_status == 1 && user()->reward_system_enabled == 1): ?>
                        <li class="nav-author-earnings"><a href="<?= adminUrl('author-earnings'); ?>"><i class="fa fa-money" aria-hidden="true"></i><span><?= trans("my_earnings"); ?></span></a></li>
                    <?php endif;
                    if (isSuperAdmin()): ?>
                        <li>
                            <div class="database-backup">
                                <form action="<?= base_url('Admin/downloadDatabaseBackup'); ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-block"><i class="fa fa-download"></i>&nbsp;&nbsp;<?= trans("download_database_backup"); ?></button>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="header">&nbsp;</li>
                </ul>
            </div>
        </section>
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
    <div class="content-wrapper">
        <section class="content" style="min-height: 1180px;">
            <div class="row">
                <div class="col-sm-12">
                    <?= view('admin/includes/_messages'); ?>
                </div>
            </div>