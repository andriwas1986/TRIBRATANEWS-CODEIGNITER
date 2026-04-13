<?php

namespace App\Controllers;

use App\Models\SkmModel;

class AdminSkmController extends BaseAdminController
{
    protected $skmModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->skmModel = new SkmModel();
    }

    /**
     * SKM List View (Admin)
     */
    public function index()
    {
        checkPermission('admin_panel');
        $data = [
            'title' => 'Daftar Survei SKM',
            'surveys' => $this->skmModel->getSurveys()
        ];

        echo view('admin/includes/_header', $data);
        echo view('admin/skm/index', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * SKM Statistics View (Admin)
     */
    public function statistics()
    {
        checkPermission('admin_panel');
        $data = [
            'title' => 'Statistik SKM',
            'stats' => $this->skmModel->getStatistics(),
            'overallAvg' => $this->skmModel->getOverallAvg(),
            'yearlyStats' => $this->skmModel->getYearlyStatistics(),
            'monthlyStats' => $this->skmModel->getMonthlyStatistics(date('Y'))
        ];

        echo view('admin/includes/_header', $data);
        echo view('admin/skm/statistics', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Delete SKM Entry
     */
    public function delete($id)
    {
        checkPermission('admin_panel');
        if ($this->skmModel->delete($id)) {
            setSuccessMessage("Data survei berhasil dihapus.");
        } else {
            setErrorMessage("Gagal menghapus data survei.");
        }
        return redirect()->to(adminUrl('skm'));
    }

    public function seed()
    {
        checkPermission('admin_panel');
        $confirm = $this->request->getGet('confirm');
        if ($confirm != 'yes') {
            return redirect()->to(adminUrl('skm/statistics'));
        }

        $services = ['SPKT', 'SKCK', 'SIM'];
        
        // Clear old dummy data if exists (using truncate for speed)
        $this->skmModel->truncate();

        // 800 Sangat Puas (Rating 4)
        for ($i = 0; $i < 800; $i++) {
            $this->skmModel->insert($this->generateMockData(4, $services));
        }

        // 200 Puas (Rating 3)
        for ($i = 0; $i < 200; $i++) {
            $this->skmModel->insert($this->generateMockData(3, $services));
        }

        setSuccessMessage("Populasi Data Berhasil: 1000 responden ditambahkan (SPKT, SIM, SKCK).");
        return redirect()->to(adminUrl('skm/statistics'));
    }

    private function generateMockData($rating, $services)
    {
        // Spread dates across the last 2 years for yearly/monthly table testing
        $randomDays = rand(0, 730);
        $createdAt = date('Y-m-d H:i:s', strtotime('-' . $randomDays . ' days'));

        $data = [
            'name' => 'Responden ' . rand(1000, 9999),
            'phone' => '08' . rand(111111111, 999999999),
            'service_type' => $services[array_rand($services)],
            'user_ip' => '127.0.0.1',
            'r1' => $rating, 'r2' => $rating, 'r3' => $rating,
            'r4' => $rating, 'r5' => $rating, 'r6' => $rating,
            'r7' => $rating, 'r8' => $rating, 'r9' => $rating,
            'suggestion' => ($rating == 4) ? 'Sangat memuaskan. Pelayanan cepat dan transparan.' : 'Sudah baik, tingkatkan progresnya.',
            'created_at' => $createdAt
        ];
        $total = $rating * 9;
        $data['total_score'] = $total;
        $data['average_score'] = round($total / 9, 2);
        return $data;
    }

}
