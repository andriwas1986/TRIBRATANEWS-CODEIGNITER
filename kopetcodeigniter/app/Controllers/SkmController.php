<?php

namespace App\Controllers;

use App\Models\SkmModel;

class SkmController extends BaseController
{
    protected $skmModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->skmModel = new SkmModel();
    }

    /**
     * Handle SKM Submission (Public)
     */
    public function submit()
    {
        if ($this->request->isAJAX()) {
            $serviceType = inputPost('service_type');
            $allowedServices = ['SPKT', 'SIM', 'SKCK'];
            
            if (!in_array($serviceType, $allowedServices)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Layanan tersebut tidak valid.'
                ]);
            }

            $data = [
                'name'          => inputPost('name'),
                'phone'         => inputPost('phone'),
                'service_type'  => $serviceType,
                'user_ip'       => $this->request->getIPAddress(),
                'r1'            => clrNum(inputPost('r1')),
                'r2'            => clrNum(inputPost('r2')),
                'r3'            => clrNum(inputPost('r3')),
                'r4'            => clrNum(inputPost('r4')),
                'r5'            => clrNum(inputPost('r5')),
                'r6'            => clrNum(inputPost('r6')),
                'r7'            => clrNum(inputPost('r7')),
                'r8'            => clrNum(inputPost('r8')),
                'r9'            => clrNum(inputPost('r9')),
                'suggestion'    => inputPost('suggestion'),
                'created_at'    => date('Y-m-d H:i:s')
            ];

            // Calculate scores
            $total = $data['r1'] + $data['r2'] + $data['r3'] + $data['r4'] + $data['r5'] + $data['r6'] + $data['r7'] + $data['r8'] + $data['r9'];
            $data['total_score'] = $total;
            $data['average_score'] = round($total / 9, 2);

            if ($this->skmModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Survei Anda telah berhasil dikirim. Terima kasih atas partisipasi Anda!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat mengirim survei. Silakan coba lagi.'
                ]);
            }
        }
        return redirect()->to(base_url());
    }
}
