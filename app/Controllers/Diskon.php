<?php

namespace App\Controllers;

use App\Models\DiskonModel;
use CodeIgniter\Controller;

class Diskon extends Controller
{
    protected $diskonModel;

    public function __construct()
    {
        $this->diskonModel = new DiskonModel();
    }

    public function index()
    {
        $diskon = $this->diskonModel->findAll();

        // CARI DISKON HARI INI
        $tanggalHariIni = date('Y-m-d');
        $diskonHariIni = $this->diskonModel->where('tanggal', $tanggalHariIni)->first();
        $nominalDiskon = $diskonHariIni['nominal'] ?? 0;

        return view('v_diskon', [
            'diskon' => $diskon,
            'diskon_hari_ini' => $nominalDiskon
        ]);
    }

    public function save()
    {
        $tanggal = $this->request->getPost('tanggal');
        $nominal = $this->request->getPost('nominal');

        // Validasi tanggal tidak boleh sama
        $cekTanggal = $this->diskonModel->where('tanggal', $tanggal)->first();
        if ($cekTanggal) {
            return redirect()->to('/diskon')->with('error', 'Diskon untuk tanggal ini sudah ada!');
        }

        $this->diskonModel->insert([
            'tanggal' => $tanggal,
            'nominal' => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Jika tanggal yang disimpan adalah hari ini, set session diskon
        if ($tanggal == date('Y-m-d')) {
            session()->set('diskon', $nominal);
        }

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil ditambahkan');
    }

    public function update($id)
    {
        $nominal = $this->request->getPost('nominal');
        $diskon = $this->diskonModel->find($id);

        $this->diskonModel->update($id, [
            'nominal' => $nominal,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Jika tanggal diskon yang diubah adalah hari ini, perbarui session
        if ($diskon && $diskon['tanggal'] == date('Y-m-d')) {
            session()->set('diskon', $nominal);
        }

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil diupdate');
    }

    public function delete($id)
    {
        $diskon = $this->diskonModel->find($id);
        $this->diskonModel->delete($id);

        // Jika diskon hari ini dihapus, hapus juga session
        if ($diskon && $diskon['tanggal'] == date('Y-m-d')) {
            session()->remove('diskon');
        }

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil dihapus');
    }
}
