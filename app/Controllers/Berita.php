<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BeritaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Berita extends BaseController
{
    public function index()
    {
        $berita = new BeritaModel();
        $getdata = $berita->findAll();
        $data['list'] = $getdata;

        return view('home', $data);
    }

    public function preview($id)
    {
        //Mengambil data dahulu
        $berita = new BeritaModel();
        $data['news'] = $berita->where(['id' => $id])->first();

        //cek apakah data kosong

        if (!$data['news']) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('detail_berita', $data);
    }
    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $berita = new BeritaModel();
            $berita->insert([
                "judul" => $this->request->getPost('judul'),
                "isi" => $this->request->getPost('isi'),
                "gambar" => $this->request->getPost('gambar')
            ]);
            return redirect('/');
        }
        return view('create_berita');
    }
    public function edit($id)
    {
        //ambil data diedit
        $berita = new BeritaModel();
        $data['news'] = $berita->where('id', $id)->first();

        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $berita = new BeritaModel();
            $berita->update($id, [
                "judul" => $this->request->getPost('judul'),
                "isi" => $this->request->getPost('isi'),
                "gambar" => $this->request->getPost('gambar')
            ]);
            return redirect('/');
        }
        return view('edit_berita', $data);
    }
    public function delete($id)
    {
        $berita = new BeritaModel();
        $berita->delete($id);
        return redirect('/');
    }
}
