<?php

namespace App\Controllers;

use App\Models\SkmModel;

class AdminSkmController extends BaseAdminController
{
    protected $skmModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        if (!hasPermission('admin_panel')) {
            exit();
        }
        $this->skmModel = new SkmModel();
    }

    /**
     * SKM List View (Admin)
     */
    public function index()
    {
        $data = [
            'title' => 'Daftar Survei SKM',
            'surveys' => $this->skmModel->getSurveys()
        ];

        echo loadView('admin/includes/_header', $data);
        echo view('admin/skm/index', $data);
        echo loadView('admin/includes/_footer');
    }

    /**
     * SKM Statistics View (Admin)
     */
    public function statistics()
    {
        $data = [
            'title' => 'Statistik SKM',
            'stats' => $this->skmModel->getStatistics(),
            'overallAvg' => $this->skmModel->getOverallAvg()
        ];

        echo loadView('admin/includes/_header', $data);
        echo view('admin/skm/statistics', $data);
        echo loadView('admin/includes/_footer');
    }

    /**
     * Delete SKM Entry
     */
    public function delete($id)
    {
        if ($this->skmModel->delete($id)) {
            setSuccessMessage("Data survei berhasil dihapus.");
        } else {
            setErrorMessage("Gagal menghapus data survei.");
        }
        return redirect()->to(adminUrl('skm'));
    }
}
