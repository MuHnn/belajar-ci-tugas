<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $tanggal_awal = date('Y-m-d');
        $nominal_diskon = [100000, 200000, 300000];

        for ($i = 0; $i < 10; $i++) {
            $data = [
                'tanggal' => date('Y-m-d', strtotime("+$i days", strtotime($tanggal_awal))),
                'nominal' => $nominal_diskon[array_rand($nominal_diskon)],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
            //print_r($data);
            $this->db->table('diskon')->insert($data);
        }
    }
}
