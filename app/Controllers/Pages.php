<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Test Web Programming'
        ];
        return view('pages/home', $data);
    }

    public function kategori()
    {
        $data = [
            'title' => 'Kategori | Test Web Programming'
        ];
        return view('pages/kategori', $data);
    }

    public function produk()
    {
        $data = [
            'title' => 'Produk | Test Web Programming'
        ];
        return view('pages/produk', $data);
    }

    public function laporan()
    {
        $data = [
            'title' => 'Laporan | Test Web Programming'
        ];
        return view('pages/laporan', $data);
    }
}
