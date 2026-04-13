<?php

namespace App\Models;

use CodeIgniter\Model;

class SkmModel extends Model
{
    protected $table = 'skm_surveys';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'phone', 'service_type', 'user_ip', 
        'r1', 'r2', 'r3', 'r4', 'r5', 'r6', 'r7', 'r8', 'r9', 
        'total_score', 'average_score', 'suggestion', 'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->createSkmTable();
    }

    /**
     * Auto-create table if not exists
     */
    private function createSkmTable()
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists($this->table)) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'service_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'user_ip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                // 9 Indicators (Standard Permenpan RB)
                'r1' => ['type' => 'TINYINT', 'constraint' => 1], // Persyaratan
                'r2' => ['type' => 'TINYINT', 'constraint' => 1], // Prosedur
                'r3' => ['type' => 'TINYINT', 'constraint' => 1], // Waktu
                'r4' => ['type' => 'TINYINT', 'constraint' => 1], // Biaya
                'r5' => ['type' => 'TINYINT', 'constraint' => 1], // Produk Layanan
                'r6' => ['type' => 'TINYINT', 'constraint' => 1], // Kompetensi
                'r7' => ['type' => 'TINYINT', 'constraint' => 1], // Perilaku
                'r8' => ['type' => 'TINYINT', 'constraint' => 1], // Sarana Prasarana
                'r9' => ['type' => 'TINYINT', 'constraint' => 1], // Penanganan Pengaduan
                'total_score' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'average_score' => [
                    'type' => 'DECIMAL',
                    'constraint' => '5,2',
                ],
                'suggestion' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->createTable($this->table);
        }
    }

    public function getSurveys()
    {
        return $this->whereIn('service_type', ['SPKT', 'SIM', 'SKCK'])
            ->orderBy('id DESC')
            ->get()->getResult();
    }

    public function getStatistics()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('service_type, COUNT(*) as total, AVG(average_score) as avg_score')
            ->whereIn('service_type', ['SPKT', 'SIM', 'SKCK'])
            ->groupBy('service_type')
            ->get()->getResult();
    }

    public function getOverallAvg()
    {
        $db = \Config\Database::connect();
        $res = $db->table($this->table)
            ->select('AVG(average_score) as avg')
            ->whereIn('service_type', ['SPKT', 'SIM', 'SKCK'])
            ->get()->getRow();
        return $res ? number_format($res->avg, 2) : 0;
    }

    public function getYearlyStatistics()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('YEAR(created_at) as year, AVG(average_score) as avg_score, COUNT(*) as total')
            ->whereIn('service_type', ['SPKT', 'SIM', 'SKCK'])
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get()->getResult();
    }

    public function getMonthlyStatistics($year = null)
    {
        if (!$year) $year = date('Y');
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('MONTH(created_at) as month, AVG(average_score) as avg_score, COUNT(*) as total')
            ->where('YEAR(created_at)', $year)
            ->whereIn('service_type', ['SPKT', 'SIM', 'SKCK'])
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()->getResult();
    }
}

