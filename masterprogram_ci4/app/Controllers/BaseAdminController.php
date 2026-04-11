<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\CommonModel;
use App\Models\PostModel;
use App\Models\SettingsModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Globals;
use Psr\Log\LoggerInterface;

abstract class BaseAdminController extends Controller
{
    protected $request;
    protected $helpers = ['text', 'cookie', 'security'];
    public $session;
    public $generalSettings;
    public $settings;
    public $activeLanguages;
    public $activeTheme;
    public $activeLang;
    public $aiWriter;
    public $categories;
    public $perPage;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->response->setHeader('Content-Security-Policy', "frame-ancestors 'none';");

        // =================================================================================
        // --- [MODIFIKASI MULTI-SITE: AUTO-CREATE SYSTEM] ---
        // =================================================================================
        
        $db = \Config\Database::connect();
        $host = $_SERVER['HTTP_HOST']; 
        
        // Bersihkan atribut
        $cleanHost = strtolower($host);
        $cleanHost = str_replace(['http://', 'https://', 'www.'], '', $cleanHost);
        $cleanHost = rtrim($cleanHost, '/');

        $siteId = 1; 
        $debugMessage = "";

        if ($db->tableExists('sites')) {
            // 1. Cari Website
            $site = $db->table('sites')->where('domain', $cleanHost)->get()->getRow();
            
            if (empty($site)) {
                $parts = explode('.', $cleanHost);
                if (count($parts) > 2) {
                    $rootDomain = $parts[count($parts)-2] . '.' . $parts[count($parts)-1];
                    $site = $db->table('sites')->where('domain', $rootDomain)->get()->getRow();
                }
            }

            // 2. JIKA KETEMU, PAKAI ID-NYA
            if ($site) {
                $siteId = $site->id;
                $debugMessage = "MATCH FOUND: ID $siteId ($site->domain)";
            } 
            // 3. JIKA TIDAK KETEMU -> OTOMATIS BUAT DATABASE BARU! (AUTO-CREATE)
            else {
                // A. Insert ke tabel 'sites'
                $newSiteData = [
                    'domain' => $cleanHost,
                    'site_name' => 'Web ' . $cleanHost,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                // Cek kolom tabel sites jika ada kolom wajib lain, sistem akan bypass error
                try {
                    $db->table('sites')->insert($newSiteData);
                    $newSiteId = $db->insertID();

                    if ($newSiteId) {
                        $siteId = $newSiteId;

                        // B. Duplikasi 'general_settings' dari Master (ID 1)
                        $masterGeneral = $db->table('general_settings')->where('site_id', 1)->get()->getRowArray();
                        if ($masterGeneral) {
                            unset($masterGeneral['id']); // Buang ID lama
                            $masterGeneral['site_id'] = $newSiteId; // Set ke ID Baru
                            // KOSONGKAN LOGO AGAR TIDAK BENTROK
                            $masterGeneral['logo'] = '';
                            $masterGeneral['logo_footer'] = '';
                            $masterGeneral['logo_email'] = '';
                            $masterGeneral['favicon'] = '';
                            
                            $db->table('general_settings')->insert($masterGeneral);
                        }

                        // C. Duplikasi 'settings' (Bahasa) dari Master (ID 1)
                        $masterSettings = $db->table('settings')->where('site_id', 1)->get()->getResultArray();
                        if ($masterSettings) {
                            foreach ($masterSettings as $mSet) {
                                unset($mSet['id']);
                                $mSet['site_id'] = $newSiteId;
                                $db->table('settings')->insert($mSet);
                            }
                        }

                        $debugMessage = "✨ AUTO CREATED NEW SITE! ID: $siteId ($cleanHost)";
                    }
                } catch (\Exception $e) {
                    $debugMessage = "AUTO CREATE FAILED (DB Error). Defaulting to ID 1.";
                }
            }
        }

        // 4. SET KONSTANTA GLOBAL
        if (!defined('CURRENT_SITE_ID')) define('CURRENT_SITE_ID', (int)$siteId);
        
        $mediaBaseUrl = rtrim(base_url(), '/'); 
        if (!defined('MEDIA_BASE_URL')) define('MEDIA_BASE_URL', $mediaBaseUrl . '/');

        // =================================================================================
        
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $settingsModel = new SettingsModel();

        // Load General Settings berdasarkan Site ID
        $this->generalSettings = $settingsModel->getGeneralSettings();
        
        // Fallback darurat
        if (empty($this->generalSettings)) {
             $this->generalSettings = $db->table('general_settings')->where('site_id', 1)->get()->getRow();
        }
        
        Globals::$generalSettings = $this->generalSettings;

        $activeLangId = $this->session->get('vr_control_panel_lang');
        if (empty($activeLangId)) {
            $activeLangId = Globals::$generalSettings->site_lang;
        }
        Globals::setActiveLanguage($activeLangId);
        
        $this->settings = $settingsModel->getSettings($activeLangId);
        Globals::$settings = $this->settings;

        $this->activeLanguages = Globals::$languages;

        if (!authCheck()) {
            redirectToUrl(adminUrl('login'));
            exit();
        }

        if ($this->generalSettings->maintenance_mode_status == 1) {
            if (!isSuperAdmin()) {
                $authModel = new AuthModel();
                $authModel->logout();
                redirectToUrl(adminUrl('login'));
                exit();
            }
        }

        $this->activeTheme = getActiveTheme();
        $this->activeLang = Globals::$activeLang;
        $this->aiWriter = aiWriter();
        $this->categories = getCategories();
        
        $this->perPage = 15;
        if (!empty(clrNum(inputGet('show')))) {
            $this->perPage = clrNum(inputGet('show'));
        }

        $view = \Config\Services::renderer();
        $view->setData([
            'activeLang' => $this->activeLang, 
            'activeLanguages' => $this->activeLanguages, 
            'activeTheme' => $this->activeTheme, 
            'generalSettings' => $this->generalSettings, 
            'baseSettings' => $this->settings, 
            'baseAIWriter' => $this->aiWriter, 
            'baseCategories' => $this->categories,
            'adminTheme' => $settingsModel->getDashboardTheme()
        ]);

       
    }
}