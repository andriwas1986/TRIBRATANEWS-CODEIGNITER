<?php

namespace App\Controllers;

class SitesController extends BaseAdminController
{
    public function index()
    {
        checkSuperAdmin();
        
        $db = \Config\Database::connect();
        
        // Cek apakah tabel sites sudah dibuat?
        if (!$db->tableExists('sites')) {
            return redirect()->to(adminUrl('multisite-setup'));
        }

        $data['title'] = 'Kelola Domain / Cabang';
        $data['sites'] = $db->table('sites')->get()->getResult();

        echo view('admin/includes/_header', $data);
        echo view('admin/multisite/index', $data);
        echo view('admin/includes/_footer');
    }

    public function add_site()
    {
        checkSuperAdmin();
        
        $val = \Config\Services::validation();
        $val->setRule('domain', 'Domain', 'required|is_unique[sites.domain]');
        $val->setRule('site_name', 'Nama Situs', 'required');

        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $data = [
            'domain'    => inputPost('domain'), // misal: jogja.news.com
            'site_name' => inputPost('site_name'),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $db->table('sites')->insert($data);
        
        // --- AUTO FIX SETTINGS SAAT NAMBAH SITE BARU ---
        // Panggil fungsi fix database agar site baru langsung punya settingan
        $this->fix_settings_logic(); 
        // -----------------------------------------------

        setSuccessMessage("Domain baru berhasil ditambahkan!");
        return redirect()->to(adminUrl('sites'));
    }

    public function delete($id)
    {
        checkSuperAdmin();
        if ($id == 1) {
            setErrorMessage("Domain Utama tidak boleh dihapus!");
            return redirect()->back();
        }
        
        $db = \Config\Database::connect();
        $db->table('sites')->where('id', $id)->delete();
        // Hapus juga settingan terkait agar database bersih
        $db->table('general_settings')->where('site_id', $id)->delete();
        $db->table('settings')->where('site_id', $id)->delete();

        setSuccessMessage("Domain berhasil dihapus.");
        return redirect()->back();
    }

    // --- FUNGSI PERBAIKAN DATABASE (Bisa dipanggil via URL) ---
    // Akses: domain.com/pokok-awuren/sites/fix_settings_database
    public function fix_settings_database()
    {
        checkSuperAdmin();
        $this->fix_settings_logic();
        echo "Perbaikan Selesai! Silakan kembali ke dashboard.";
    }

    // Logika Inti Perbaikan Database
    private function fix_settings_logic()
    {
        $forge = \Config\Database::forge();
        $db    = \Config\Database::connect();

        // 1. Cek & Tambah kolom site_id di general_settings jika belum ada
        if ($db->tableExists('general_settings')) {
            if (!$db->fieldExists('site_id', 'general_settings')) {
                $forge->addColumn('general_settings', [
                    'site_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 1, 'after' => 'id']
                ]);
            }
        }

        // 2. Ambil semua site
        $sites = $db->table('sites')->get()->getResult();
        
        // Ambil Data Master (ID 1) untuk dicopy
        $masterGenSettings = $db->table('general_settings')->where('site_id', 1)->get()->getRowArray();
        $masterSettings    = $db->table('settings')->where('site_id', 1)->get()->getResultArray();

        if (!$masterGenSettings) {
            // Fallback jika ID 1 belum punya site_id (kasus migrasi lama)
            $masterGenSettings = $db->table('general_settings')->get()->getRowArray();
        }

        foreach ($sites as $site) {
            $siteId = $site->id;
            
            // Skip jika master (ID 1) sudah benar, tapi pastikan ID 1 punya data dgn site_id=1
            if ($siteId == 1) {
                $cekMaster = $db->table('general_settings')->where('site_id', 1)->countAllResults();
                if ($cekMaster == 0 && $masterGenSettings) {
                    $db->table('general_settings')->where('id', $masterGenSettings['id'])->update(['site_id' => 1]);
                }
                continue; 
            }

            // A. COPY GENERAL SETTINGS
            $cekGen = $db->table('general_settings')->where('site_id', $siteId)->countAllResults();
            if ($cekGen == 0 && $masterGenSettings) {
                $newGen = $masterGenSettings;
                unset($newGen['id']); // Hapus ID lama
                $newGen['site_id'] = $siteId; // Set ID baru
                
                // Opsional: Ubah app name sedikit biar ketahuan bedanya
                // $newGen['application_name'] = $site->site_name; 
                
                $db->table('general_settings')->insert($newGen);
            }

            // B. COPY SETTINGS (BAHASA)
            foreach ($masterSettings as $row) {
                $cekSet = $db->table('settings')->where('site_id', $siteId)->where('lang_id', $row['lang_id'])->countAllResults();
                if ($cekSet == 0) {
                    $newSet = $row;
                    unset($newSet['id']);
                    $newSet['site_id'] = $siteId;
                    
                    // Update nama aplikasi sesuai nama site
                    $newSet['application_name'] = $site->site_name; 
                    
                    $db->table('settings')->insert($newSet);
                }
            }
        }
    }
}