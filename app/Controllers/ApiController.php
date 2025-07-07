<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class ApiController extends ResourceController
{
    protected $apiKey;
    protected $user;
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        $this->apiKey = env('API_KEY');
        $this->user = new UserModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    public function index()
    {
        $data = [ 
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        $headers = $this->request->headers(); 

        array_walk($headers, function (&$value, $key) {
            $value = $value->getValue();
        });

        if(array_key_exists("Key", $headers)){
            if ($headers["Key"] == $this->apiKey) {
                $penjualan = $this->transaction->findAll();
            
                foreach ($penjualan as &$pj) {
                    $details = $this->transaction_detail
                        ->where('transaction_id', $pj['id'])
                        ->findAll();

                    // Tambah data ke transaksi
                    $pj['details'] = $details;
                    $pj['jumlah_item'] = array_sum(array_column($details, 'jumlah'));
                    $pj['status'] = $pj['status'] == 1 ? 'Sudah Selesai' : 'Belum Selesai';
                }

                $data['status'] = ["code" => 200, "description" => "OK"];
                $data['results'] = $penjualan;
            }
        } 

        return $this->respond($data);   
    }
}
