<?php 
$menuLimit = $generalSettings->menu_limit; 

// =========================================================
// TARIK PENGATURAN MEGA MENU & ICON DARI DATABASE
// =========================================================
$db = \Config\Database::connect();

$megaMenuConfig = [];
if ($db->tableExists('custom_megamenu')) {
    $configs = $db->table('custom_megamenu')->get()->getResultArray();
    foreach($configs as $cfg) {
        $megaMenuConfig[$cfg['menu_slug']] = $cfg;
    }
}

$customSubIcons = [];
if ($db->tableExists('custom_submenu_icons')) {
    $subIconConfigs = $db->table('custom_submenu_icons')->get()->getResultArray();
    foreach($subIconConfigs as $subCfg) {
        $customSubIcons[$subCfg['sub_slug']] = [
            'icon'  => $subCfg['sub_icon'],
            'image' => isset($subCfg['sub_image']) ? $subCfg['sub_image'] : '',
            'desc'  => isset($subCfg['sub_desc']) ? $subCfg['sub_desc'] : ''
        ];
    }
}
// =========================================================
?>

<style>
    .mega-custom-page { padding: 25px; border-radius: 0 0 12px 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border-top: 3px solid #00a8ff; background: #fff; }
    
    .mega-divider-left { border-right: 1px solid #e2e8f0; padding-right: 25px; display: flex; align-items: center; justify-content: center; }
    .mega-divider-right { border-left: 1px solid #e2e8f0; padding-left: 25px; display: flex; align-items: center; justify-content: center; }
    
    /* Layout Aplikasi Grid Baru */
    .mega-custom-link { 
        display: flex; align-items: flex-start; padding: 12px; transition: all 0.3s ease; 
        border-radius: 8px; text-decoration: none !important; margin-bottom: 10px;
    }
    .mega-custom-link:hover { background-color: #f8fafc; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    
    .mega-custom-icon { 
        width: 42px; height: 42px; flex-shrink: 0; background: #f1f5f9; color: #475569; 
        border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px; 
    }
    .mega-custom-icon i { font-size: 18px; }
    .mega-custom-icon img { width: 28px; height: 28px; object-fit: contain; } 
    
    .mega-item-meta { display: flex; flex-direction: column; justify-content: center; }
    .mega-item-title { font-weight: 700; font-size: 14px; color: #1e293b; margin-bottom: 3px; transition: color 0.2s; line-height: 1.2; }
    .mega-item-desc { font-size: 11px; color: #64748b; line-height: 1.4; font-weight: 500; }
    
    .mega-custom-link:hover .mega-item-title { color: #00a8ff; }

    /* Dark Mode Auto-Switch */
    .dark-mode .mega-custom-page { background: #1a1a24 !important; border-top: 3px solid #0ea5e9; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
    .dark-mode .mega-custom-link:hover { background-color: #2a2a3c !important; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    .dark-mode .mega-custom-icon { background: #334155 !important; color: #e2e8f0 !important; }
    .dark-mode .mega-item-title { color: #f8fafc !important; }
    .dark-mode .mega-custom-link:hover .mega-item-title { color: #38bdf8 !important; }
    .dark-mode .mega-item-desc { color: #94a3b8 !important; }
    
    .dark-mode .mega-custom-header-title { color: #f8fafc !important; }
    .dark-mode .mega-custom-header-desc { color: #94a3b8 !important; }
    .dark-mode .mega-custom-border { border-bottom: 1px solid #334155 !important; }
    .dark-mode .mega-divider-left { border-right: 1px solid #334155 !important; }
    .dark-mode .mega-divider-right { border-left: 1px solid #334155 !important; }
    
    /* FIX: Force Home/Active link to be Dark with White Text (Override Leakage) */
    .navbar-default .navbar-nav > .active > a, 
    .navbar-default .navbar-nav > .active > a:hover, 
    .navbar-default .navbar-nav > .active > a:focus {
        background-color: #161616 !important;
        color: #ffffff !important;
        border-bottom: 3px solid #ff0000 !important;
    }
</style>

<nav class="navbar navbar-default main-menu megamenu">
<div class="container">
<div class="collapse navbar-collapse">
<div class="row">
<ul class="nav navbar-nav">
<?php if ($generalSettings->show_home_link == 1): ?>
<li class="<?= uri_string() == 'index' || uri_string() == '' || uri_string() == '/' ? 'active' : ''; ?>"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
<?php endif;
$totalItem = 0;
$i = 1;
if (!empty($baseMenuLinks)):
    foreach ($baseMenuLinks as $item):
        if ($item->item_visibility == 1 && $item->item_location == "main" && $item->item_parent_id == '0'):
            if ($i < $menuLimit):
                $subLinks = getSubMenuLinks($baseMenuLinks, $item->item_id, $item->item_type);
                
                if ($item->item_type == "category"):
                    if (!empty($subLinks)) { echo loadView('nav/_megamenu_multicategory', ['categoryId' => $item->item_id]); } 
                    else { echo loadView('nav/_megamenu_singlecategory', ['categoryId' => $item->item_id]); }
                
                else:
                    if (!empty($subLinks)): 
                        $menuSlug = isset($item->item_slug) ? $item->item_slug : strSlug($item->item_name);
                        $activeConfig = isset($megaMenuConfig[$menuSlug]) ? $megaMenuConfig[$menuSlug] : null;
                        
                        $style = $activeConfig ? $activeConfig['menu_style'] : 'standard';
                        $bgImage = $activeConfig ? (strpos($activeConfig['menu_image'], 'http') === false ? base_url($activeConfig['menu_image']) : $activeConfig['menu_image']) : '';
                        $bgTitle = $activeConfig ? $activeConfig['menu_title'] : esc($item->item_name);
                        $bgDesc  = $activeConfig ? $activeConfig['menu_desc'] : '';
                        ?>
                        
                        <?php if ($style == 'standard'): ?>
                            <li class="dropdown <?= uri_string() == $item->item_slug ? 'active' : ''; ?>">
                                <a class="dropdown-toggle disabled no-after" data-toggle="dropdown" href="<?= generateMenuItemURL($item, $baseCategories); ?>"><?= esc($item->item_name); ?><span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-more dropdown-top">
                                    <?php foreach ($subLinks as $subItem): if ($subItem->item_visibility == 1): ?>
                                        <li><a role="menuitem" href="<?= generateMenuItemURL($subItem, $baseCategories); ?>"><?= esc($subItem->item_name); ?></a></li>
                                    <?php endif; endforeach; ?>
                                </ul>
                            </li>
                        
                        <?php else: ?>
                            <li class="dropdown megamenu-fw <?= uri_string() == $item->item_slug ? 'active' : ''; ?>">
                                <a class="dropdown-toggle disabled no-after" data-toggle="dropdown" href="<?= generateMenuItemURL($item, $baseCategories); ?>">
                                    <?= esc($item->item_name); ?><span class="caret"></span>
                                </a>
                                
                                <ul class="dropdown-menu megamenu-content dropdown-top mega-custom-page">
                                    <li>
                                        <div class="row">
                                            <?php ob_start(); ?>
                                                <div class="col-sm-4 hidden-xs <?= $style=='banner_left' ? 'mega-divider-left' : 'mega-divider-right'; ?>">
                                                    <?php if(!empty($bgImage)): ?>
                                                        <img src="<?= $bgImage; ?>" alt="<?= esc($bgTitle); ?>" style="width: 100%; height: auto; max-height: 280px; object-fit: contain; border-radius: 8px;">
                                                    <?php else: ?>
                                                        <div style="width: 100%; padding: 40px 20px; background: linear-gradient(135deg, #0ea5e9, #2563eb); border-radius: 8px; text-align: center; color: #fff; box-shadow: 0 4px 15px rgba(14,165,233,0.3);">
                                                            <i class="icon-folder" style="font-size: 50px; opacity: 0.9;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php $htmlBanner = ob_get_clean(); ?>

                                            <?php if($style == 'banner_left') echo $htmlBanner; ?>

                                            <div class="col-sm-8">
                                                <div class="mega-custom-border" style="padding: 0 10px 12px 10px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                                                    <h4 class="mega-custom-header-title" style="font-size: 18px; font-weight: 800; color: #1e293b; margin-top: 0; margin-bottom: 5px;">
                                                        <?= esc($bgTitle); ?>
                                                    </h4>
                                                    <?php if(!empty($bgDesc)): ?>
                                                        <p class="mega-custom-header-desc" style="font-size: 13px; color: #64748b; margin-bottom: 0; line-height: 1.5;">
                                                            <?= esc($bgDesc); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="row" style="padding: 0 5px;">
                                                    <?php foreach ($subLinks as $subItem): if ($subItem->item_visibility == 1): 
                                                        $subSlug = isset($subItem->item_slug) ? $subItem->item_slug : strSlug($subItem->item_name);
                                                        
                                                        // AMBIL DATA DARI DATABASE
                                                        $subData = isset($customSubIcons[$subSlug]) ? $customSubIcons[$subSlug] : ['icon'=>'icon-arrow-right', 'image'=>'', 'desc'=>''];
                                                        $sIconClass = !empty($subData['icon']) ? $subData['icon'] : 'icon-arrow-right';
                                                        $sImgUrl = !empty($subData['image']) ? (strpos($subData['image'], 'http') === false ? base_url($subData['image']) : $subData['image']) : '';
                                                        $sDesc = !empty($subData['desc']) ? $subData['desc'] : '';
                                                    ?>
                                                        <div class="col-sm-6">
                                                            <a href="<?= generateMenuItemURL($subItem, $baseCategories); ?>" class="mega-custom-link">
                                                                
                                                                <div class="mega-custom-icon">
                                                                    <?php if(!empty($sImgUrl)): ?>
                                                                        <img src="<?= $sImgUrl; ?>" alt="icon">
                                                                    <?php else: ?>
                                                                        <i class="<?= esc($sIconClass); ?>"></i>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <div class="mega-item-meta">
                                                                    <span class="mega-item-title"><?= esc($subItem->item_name); ?></span>
                                                                    <?php if(!empty($sDesc)): ?>
                                                                        <span class="mega-item-desc"><?= esc(characterLimiter($sDesc, 60, '...')); ?></span>
                                                                    <?php endif; ?>
                                                                </div>

                                                            </a>
                                                        </div>
                                                    <?php endif; endforeach; ?>
                                                </div>
                                            </div>

                                            <?php if($style == 'banner_right') echo $htmlBanner; ?>

                                        </div>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                    <?php else: ?>
                        <li class="<?= uri_string() == $item->item_slug ? 'active' : ''; ?>"><a href="<?= generateMenuItemURL($item, $baseCategories); ?>"><?= esc($item->item_name); ?></a></li>
                    <?php endif;
                endif;
                $i++;
            endif;
            $totalItem++;
        endif;
    endforeach;
endif;

if ($totalItem >= $menuLimit): ?>
    <li class="dropdown relative">
        <a class="dropdown-toggle dropdown-more-icon" data-toggle="dropdown" href="#"><i class="icon-ellipsis-h"></i></a>
        <ul class="dropdown-menu dropdown-more dropdown-top">
            <?php $i = 1; if (!empty($baseMenuLinks)): foreach ($baseMenuLinks as $item): if ($item->item_visibility == 1 && $item->item_location == "main" && $item->item_parent_id == "0"): if ($i >= $menuLimit):
            $subLinks = getSubMenuLinks($baseMenuLinks, $item->item_id, $item->item_type); if (!empty($subLinks)): ?>
                <li class="dropdown-more-item"><a class="dropdown-toggle disabled" data-toggle="dropdown" href="<?= generateMenuItemURL($item, $baseCategories); ?>"><?= esc($item->item_name); ?> <span class="icon-arrow-right"></span></a>
                    <ul class="dropdown-menu dropdown-sub">
                        <?php foreach ($subLinks as $subItem): if ($subItem->item_visibility == 1): ?>
                            <li><a role="menuitem" href="<?= generateMenuItemURL($subItem, $baseCategories); ?>"><?= esc($subItem->item_name); ?></a></li>
                        <?php endif; endforeach; ?>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?= generateMenuItemURL($item, $baseCategories); ?>"><?= esc($item->item_name); ?></a></li>
            <?php endif; endif; $i++; endif; endforeach; endif; ?>
        </ul>
    </li>
<?php endif; ?>
</ul>

<ul class="nav navbar-nav navbar-right">
    <li class="li-search">
        <a class="search-icon"><i class="icon-search"></i></a>
        <div class="search-form">
            <form action="<?= generateURL('search'); ?>" method="get" id="search_validate">
                <input type="text" name="q" maxlength="300" pattern=".*\S+.*" class="form-control form-input" placeholder="<?= trans("placeholder_search"); ?>" <?= $rtl == true ? 'dir="rtl"' : ''; ?> required>
                <button class="btn btn-default"><i class="icon-search"></i></button>
            </form>
        </div>
    </li>
</ul>
</div>
</div>
</div>
</nav>