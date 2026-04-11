<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SkmDummySeeder extends Seeder
{
    public function run()
    {
        $services = ['SPKT', 'SKCK', 'SIM', 'SIDIK JARI', 'IZIN KERAMAIAN', 'LAINNYA'];
        $batchSize = 100;
        
        // 800 Sangat Puas (80%)
        $this->generateData(800, 4, $services);
        
        // 200 Puas (20%)
        $this->generateData(200, 3, $services);
    }

    private function generateData($count, $rating, $services)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('skm_surveys');
        
        for ($i = 0; $i < $count; $i++) {
            $data = [
                'name'          => 'Responden ' . rand(1000, 9999),
                'phone'         => '08' . rand(111111111, 999999999),
                'service_type'  => $services[array_rand($services)],
                'user_ip'       => '127.0.0.1',
                'r1'            => $rating,
                'r2'            => $rating,
                'r3'            => $rating,
                'r4'            => $rating,
                'r5'            => $rating,
                'r6'            => $rating,
                'r7'            => $rating,
                'r8'            => $rating,
                'r9'            => $rating,
                'suggestion'    => ($rating == 4) ? 'Pelayanan sangat bagus dan cepat.' : 'Pelayanan sudah baik, tingkatkan lagi.',
                'created_at'    => date('Y-m-d H:i:s', strtotime('-' . rand(0, 30) . ' days -' . rand(0, 23) . ' hours'))
            ];

            // Calculate scores
            $total = $data['r1'] * 9;
            $data['total_score'] = $total;
            $data['average_score'] = round($total / 9, 2);

            $builder->insert($data);
        }
    }
}
