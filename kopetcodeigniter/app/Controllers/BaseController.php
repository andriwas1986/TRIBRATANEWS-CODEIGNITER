<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\CommonModel;
use App\Models\PageModel;
use App\Models\PostModel;
use App\Models\SettingsModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Globals;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 * class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [
        'text', 'cookie', 'security', 'xml'
    ];

    public $session;
    public $settingsModel;
    public $commonModel;
    public $postModel;
    public $generalSettings;
    public $settings;
    public $activeLanguages;
    public $activeTheme;
    public $activeLang;
    public $activeFonts;
    public $rtl;
    public $darkMode;
    public $widgets;
    public $menuLinks;
    public $categories;
    public $postsSelected;
    public $latestCategoryPosts;
    public $adSpaces;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // prevent iframe
        $this->response->setHeader('Content-Security-Policy', "frame-ancestors 'none';");

        // =================================================================================
        // --- [MODIFIKASI MULTI-SITE START] ---
        // =================================================================================
        
        // 1. Deteksi Domain & Setup Database Connection
        $currentDomain = $_SERVER['HTTP_HOST'];
        $db = \Config\Database::connect();
        
        $siteId = 1; // Default ke Master (ID 1)
        
        // Default URL Gambar (Local)
        // Kita trim '/' di kanan agar konsisten saat penggabungan string nanti
        $mediaBaseUrl = rtrim(base_url(), '/'); 

        // 2. Cek Site ID dari Database
        if ($db->tableExists('sites')) {
            $site = $db->table('sites')->where('domain', $currentDomain)->get()->getRow();
            if ($site) {
                $siteId = $site->id;
            }
        }

        // 3. Tentukan URL Gambar Terpusat (Centralized Media)
        // Jika bukan Web Utama, arahkan URL media ke Web Utama
        if ($siteId != 1) {
            $masterSite = $db->table('sites')->where('id', 1)->get()->getRow();
            if ($masterSite) {
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
                // URL Master tanpa slash di akhir
                $mediaBaseUrl = rtrim($protocol . $masterSite->domain, '/'); 
            }
        }

        // 4. Definisikan Constant Global
        if (!defined('CURRENT_SITE_ID')) define('CURRENT_SITE_ID', $siteId);
        
        // Pastikan MEDIA_BASE_URL selalu punya akhiran '/' agar pas digabung dengan path gambar
        if (!defined('MEDIA_BASE_URL')) define('MEDIA_BASE_URL', $mediaBaseUrl . '/');
        
        // =================================================================================
        // --- [MODIFIKASI MULTI-SITE END] ---
        // =================================================================================

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->settingsModel = new SettingsModel();
        $this->commonModel = new CommonModel();
        $this->postModel = new PostModel();

        //active languages
        $this->activeLanguages = Globals::$languages;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            setActiveLangPostRequest();
        }
        
        //active lang
        $this->activeLang = Globals::$activeLang;

        // =================================================================================
        // --- [RELOAD SETTINGS PER SUBDOMAIN] ---
        // =================================================================================
        // Kita harus menimpa data di Config\Globals agar helper 'getLogo()', 'trans()', dll
        // menggunakan data milik Subdomain ini, bukan data Web Utama.
        
        // 1. Ambil General Settings milik Site ini
        $this->generalSettings = $this->settingsModel->getGeneralSettings();
        
        // 2. Timpa Global Var
        if (!empty($this->generalSettings)) {
            Globals::$generalSettings = $this->generalSettings;
        }

        // 3. Ambil Settings (Bahasa/Translation) milik Site ini
        $this->settings = $this->settingsModel->getSettings($this->activeLang->id);
        
        // 4. Timpa Global Var
        if (!empty($this->settings)) {
            Globals::$settings = $this->settings;
        }
        // =================================================================================

        //active theme
        $this->activeTheme = Globals::$activeTheme;

        //maintenance mode
        if ($this->generalSettings->maintenance_mode_status == 1) {
            if (!isSuperAdmin()) {
                echo view('common/maintenance', ['generalSettings' => $this->generalSettings, 'baseSettings' => $this->settings]);
            }
        }
        //site fonts
        $this->activeFonts = $this->getFonts($this->settings);
        //widgets
        $this->widgets = $this->getWidgets($this->activeLang->id);
        //ad spaces
        $this->adSpaces = $this->getAdSpaces($this->activeLang->id);
        //menu links
        $this->menuLinks = $this->getMenuLinks($this->activeLang->id);
        //menu links
        $this->categories = $this->getCategories($this->activeLang->id);

        //rtl
        $this->rtl = false;
        if ($this->activeLang->text_direction == 'rtl') {
            $this->rtl = true;
        }
        $this->darkMode = Globals::$darkMode;

        $this->postsSelected = $this->postModel->getPostsSelected($this->activeLang->id);

        //latest categories posts
        $this->latestCategoryPosts = $this->postModel->getLatestCategoryPosts($this->activeLang->id);

        // check scheduled posts
        $this->postModel->checkScheduledPosts();

        //view variables
        $view = \Config\Services::renderer();
        $view->setData(['assetsPath' => 'assets/' . getThemePath(), 'activeTheme' => $this->activeTheme, 'activeLang' => $this->activeLang, 'generalSettings' => $this->generalSettings, 'baseSettings' => $this->settings, 'activeLanguages' => $this->activeLanguages, 'rtl' => $this->rtl,
            'darkMode' => $this->darkMode, 'activeFonts' => $this->activeFonts, 'baseMenuLinks' => $this->menuLinks, 'baseWidgets' => $this->widgets, 'baseCategories' => $this->categories, 'basePostsSelected' => $this->postsSelected, 'baseLatestCategoryPosts' => $this->latestCategoryPosts, 'adSpaces' => $this->adSpaces]);
    }

    //get fonts
    private function getFonts($settings)
    {
        // Add Site ID to cache key to prevent conflict
        $key = 'fonts_' . $settings->id . '_site_' . CURRENT_SITE_ID;
        return getOrSetStaticCache($key, function () use ($settings) {
            return $this->settingsModel->getSelectedFonts($settings);
        });
    }

    //get widgets
    private function getWidgets($langId)
    {
        // Add Site ID to cache key to prevent conflict
        $key = 'widgets_lang_' . $langId . '_site_' . CURRENT_SITE_ID;
        return getOrSetStaticCache($key, function () use ($langId) {
            return $this->settingsModel->getWidgetsByLang($langId);
        });
    }

    //get ad spaces
    private function getAdSpaces($langId)
    {
        // Add Site ID to cache key to prevent conflict
        $key = 'ad_spaces_lang_' . $langId . '_site_' . CURRENT_SITE_ID;
        return getOrSetStaticCache($key, function () use ($langId) {
            return $this->commonModel->getAdSpacesByLang($langId);
        });
    }

    //get menu links
    private function getMenuLinks($langId)
    {
        // Add Site ID to cache key to prevent conflict
        $key = 'menu_links_lang_' . $langId . '_site_' . CURRENT_SITE_ID;
        return getOrSetStaticCache($key, function () use ($langId) {
            $pageModel = new PageModel();
            return $pageModel->getMenuLinks($langId);
        });
    }

    //get categories
    private function getCategories($langId)
    {
        // Add Site ID to cache key to prevent conflict
        $key = 'categories_lang' . $langId . '_site_' . CURRENT_SITE_ID;
        return getOrSetStaticCache($key, function () use ($langId) {
            $model = new CategoryModel();
            return $model->getCategoriesByLang($langId);
        });
    }
}