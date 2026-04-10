<?php

namespace App\Controllers;

class MultiSiteSetupController extends BaseAdminController
{
    public function index()
    {
        // Hanya Super Admin yang boleh akses
        checkSuperAdmin(); 
        
        $data['title'] = 'Multi-Site Installation';
        // Kita pakai view admin standar agar rapi
        echo view('admin/includes/_header', $data);
        echo view('admin/multisite/setup', $data); // Nanti kita buat view ini
        echo view('admin/includes/_footer');
    }

    public function run_setup()
    {
        checkSuperAdmin();

        $forge = \Config\Database::forge();
        $db    = \Config\Database::connect();

        // 1. BUAT TABEL 'SITES' (Jika belum ada)
        if (!$db->tableExists('sites')) {
            $fields = [
                'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'domain'      => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
                'site_name'   => ['type' => 'VARCHAR', 'constraint' => 255],
                'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
            ];
            $forge->addField($fields);
            $forge->addPrimaryKey('id');
            $forge->createTable('sites', true);

            // Daftarkan Domain Saat Ini sebagai Master (ID 1)
            $currentDomain = $_SERVER['HTTP_HOST'];
            $db->table('sites')->insert([
                'domain'    => $currentDomain,
                'site_name' => 'Web Utama',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 2. MODIFIKASI TABEL LAIN (Tambah kolom site_id)
        // Sesuaikan dengan nama tabel di database Anda
        $tablesToModify = [
            'posts', 
            'categories', 
            'pages', 
            'gallery_albums', 
            'polls', 
            'settings',
            'users' // Opsional: jika user mau dipisah per web
        ];

        foreach ($tablesToModify as $tableName) {
            if ($db->tableExists($tableName)) {
                if (!$db->fieldExists('site_id', $tableName)) {
                    // Tambah kolom site_id
                    $forge->addColumn($tableName, [
                        'site_id' => [
                            'type'       => 'INT',
                            'constraint' => 11,
                            'default'    => 1,
                            'after'      => 'id'
                        ]
                    ]);
                    // Tambah Index biar query cepat
                    $db->query("ALTER TABLE $tableName ADD INDEX idx_{$tableName}_site_id (site_id)");
                }
            }
        }

        setSuccessMessage("Database berhasil di-upgrade untuk Multi-Site!");
        return redirect()->to(adminUrl('sites'));
    }
}