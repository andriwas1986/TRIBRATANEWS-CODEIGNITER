<?php
// Define the path to the front controller (index.php)
$publicPath = __DIR__;
$appPath = __DIR__ . '/masterprogram_ci4/app';

// Load the CodeIgniter 4 bootstrap
require_once $appPath . '/Config/Constants.php';
require_once $appPath . '/../vendor/autoload.php';

// Mock the environment
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';

// Initialize the database
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
    $check = $db->table('themes')->where('theme', $theme['theme'])->get()->getRow();
    if (!$check) {
        $db->table('themes')->insert($theme);
        echo "Inserted theme: " . $theme['theme_name'] . "\n";
    } else {
        echo "Theme already exists: " . $theme['theme_name'] . "\n";
    }
}

echo "Seeding completed.\n";
