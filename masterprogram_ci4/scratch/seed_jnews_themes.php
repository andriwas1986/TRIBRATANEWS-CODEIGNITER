<?php
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();

$themes = [
    [
        'theme' => 'jnews_tech',
        'theme_name' => 'Elite Tech',
        'theme_folder' => 'jnews_tech',
        'theme_color' => '#1e3799',
        'block_color' => '#1e3799',
        'mega_menu_color' => '#0a3d62',
        'is_active' => 0
    ],
    [
        'theme' => 'jnews_lifestyle',
        'theme_name' => 'Luxury Lifestyle',
        'theme_folder' => 'jnews_lifestyle',
        'theme_color' => '#60a3bc',
        'block_color' => '#60a3bc',
        'mega_menu_color' => '#3c6382',
        'is_active' => 0
    ],
    [
        'theme' => 'jnews_viral',
        'theme_name' => 'National Viral',
        'theme_folder' => 'jnews_viral',
        'theme_color' => '#eb2f06',
        'block_color' => '#eb2f06',
        'mega_menu_color' => '#b71540',
        'is_active' => 0
    ]
];

foreach ($themes as $theme) {
    // Check if exists
    $check = $db->table('themes')->where('theme', $theme['theme'])->get()->getRow();
    if (!$check) {
        $db->table('themes')->insert($theme);
        echo "Inserted: " . $theme['theme_name'] . "\n";
    } else {
        echo "Skipped: " . $theme['theme_name'] . " (Already exists)\n";
    }
}
