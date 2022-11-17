<?php

namespace App\Controllers;

use App\Models\ProdukServersideModel;
use Config\Services;

class ProdukDC extends BaseController
{

    public function getAllProduk()
    {
        if ($this->request->isAJAX()) {
            $view = [
                'data' => view('data_view/v_dataproduk'),
            ];

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function produkServerside()
    {
        if ($this->request->isAJAX()) {
            $request = Services::request();
            $ProdukServersideModel = new ProdukServersideModel($request);

            #sebelumnya -if bawah ini- $request->getMethod(true) == 'POST'
            if ($request->getPost()) {
                $lists = $ProdukServersideModel->get_datatables();
                $data = [];
                $no = $request->getPost("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];

                    $tomboledit = "<button type=\"button\" class=\"btn btn-primary btn-sm my-1\" onclick=\"editData(" . $list['id_produk'] . ")\"><i class=\"fas fa-edit\"></i> </button>";

                    $tombolhapus = "<button type=\"button\" class=\"btn btn-danger btn-sm my-1\" onclick=\"deleteData(" . $list['id_produk'] . ")\"><i class=\"fas fa-trash-alt\"></i> </button>";

                    $row[] = "<input type=\"checkbox\" class=\"checkbox_produk\" value=\"" . $list['id_produk'] . "\">";
                    $row[] = $no;
                    $row[] = $list['id_kategori-p'];
                    $row[] = $list['nama_produk'];
                    $row[] = $list['kode_produk'];
                    $row[] = $list['foto_produk'];
                    $row[] = $list['tgl_register'];
                    $row[] = $tomboledit . " " . $tombolhapus;

                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->getPost('draw'),
                    "recordsTotal" => $ProdukServersideModel->count_all(),
                    "recordsFiltered" => $ProdukServersideModel->count_filtered(),
                    "data" => $data
                ];
                echo json_encode($output);
            }
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function addProduk()
    {
        if ($this->request->isAJAX()) {
            $data['tbl_kategori'] = $this->KategoriModel->select('id_kategori, nama_kategori')->findAll();
            $view = [
                'data' => view('data_view/v_tambahdata_produk', $data)
            ];

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function saveAddProduk()
    {
        if ($this->request->isAJAX()) {
            $jumlah_data = count($this->request->getVar('nama_produk'));

            $validation = \Config\Services::validation();

            // Perulangan untuk validasi
            for ($i = 0; $i < $jumlah_data; $i++) {
                // Validasi khusus 
                if ($this->KategoriModel->select('id_kategori')->find($this->request->getVar('tbl_kategori')[$i]) == null) {
                    $error[$i]['tbl_kategori'] = 'Kategori tersebut tidak ada';
                }

                $valid = $this->validate([
                    "nama_produk.$i" => [
                        "label" => "Nama Produk",
                        "rules" => "required|max_length[200]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:200)",
                        ]
                    ],
                    "kode_produk.$i" => [
                        "label" => "Kode Produk",
                        "rules" => "required|max_length[50]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:50)",
                        ]
                    ],
                    "foto_produk.$i" => [
                        "label" => "Foto Produk",
                        "rules" => "required|max_length[50]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:50)",
                        ]
                    ],
                    "tgl_register.$i" => [
                        "label" => "Tanggal registrasi",
                        "rules" => "required|valid_date",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "valid_date" => "{field} tidak valid",
                        ]
                    ],
                ]);

                // Membuat pesan error dalam bentuk array
                $error[$i] = [
                    'nama_produk' => $validation->getError("nama_produk.$i"), 'kode_produk' => $validation->getError("kode_produk.$i"), 'foto_produk' => $validation->getError("foto_produk.$i"), 'tgl_register' => $validation->getError("tgl_register.$i"),
                ];
            }
            // Jika terdapat kesalahan
            if (!$valid) {
                $error["jumlah_data"] = $jumlah_data;
                $view = $error;
            } else {
                // Mengambil data dari request
                $tbl_kategori = $this->request->getVar('tbl_kategori');
                $nama_produk = $this->request->getVar('nama_produk');
                $kode_produk = $this->request->getVar('kode_produk');
                $foto_produk = $this->request->getVar('foto_produk');
                $tgl_register = $this->request->getVar('tgl_register');

                // Perulangan untuk menyiapkan array yang akan diinsert
                for ($i = 0; $i < $jumlah_data; $i++) {
                    $data[$i] = [
                        'id_kategori-p' => $tbl_kategori[$i],
                        'nama_produk' => htmlspecialchars($nama_produk[$i]),
                        'kode_produk' => htmlspecialchars($kode_produk[$i]),
                        'foto_produk' => htmlspecialchars($foto_produk[$i]),
                        'tgl_register' => htmlspecialchars($tgl_register[$i]),
                    ];
                }

                // Insert banyak ke database
                $this->ProdukModel->insertBatch($data);

                // Mengembalikan pesan berhasil
                $view = [
                    'success' => "$jumlah_data data tersebut berhasil ditambahkan",
                ];
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function deleteProduk()
    {
        if ($this->request->isAJAX()) {
            $id_produk = $this->request->getVar('id_produk');
            $jmldata = count($id_produk);
            $jmlditemukan = count($this->ProdukModel->select('id_produk')->find($id_produk));

            $this->ProdukModel->delete($id_produk);

            // Jika data yang dihapus hanya 1
            if ($jmldata == 1) {
                // Jika jumlah data yang ditemukan sama dengan jumlah data yang ingin dihapus
                if ($jmlditemukan == $jmldata) {
                    $view = [
                        'success' => "Data tersebut berhasil dihapus",
                    ];
                } else {
                    $view = [
                        'error' => "Data tersebut tidak ditemukan",
                    ];
                }
            } else {
                if ($jmlditemukan == null) {
                    $view = [
                        'error' => "$jmldata data tersebut tidak ditemukan",
                    ];
                } else if ($jmlditemukan == $jmldata) {
                    $view = [
                        'success' => "$jmldata data tersebut berhasil dihapus",
                    ];
                } else {
                    $view = [
                        'warning' => "$jmlditemukan data berhasil dihapus, tapi " . ($jmldata - $jmlditemukan) . ' data lainnya tidak ditemukan',
                    ];
                }
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function editProduk()
    {
        if ($this->request->isAJAX()) {
            $data['tbl_produk'] = $this->ProdukModel->select('id_produk, id_kategori-p, nama_produk, kode_produk, foto_produk, tgl_register')->find($this->request->getVar('id_produk'));
            $data['tbl_kategori'] = $this->KategoriModel->select('id_kategori, nama_kategori')->findAll();

            // Jika data yang akan dihapus tidak ditemukan
            if ($data['tbl_produk'] == null) {
                $view = [
                    'error' => "Data tersebut tidak ditemukan",
                ];
            } else {
                $view = [
                    'data' => view('data_view/v_editdata_produk', $data),
                ];
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function saveEditProduk()
    {
        if ($this->request->isAJAX()) {
            $jumlah_data = count($this->request->getVar('id_produk'));

            $validation = \Config\Services::validation();

            // Perulangan untuk validasi
            for ($i = 0; $i < $jumlah_data; $i++) {
                $valid = $this->validate([
                    "nama_produk.$i" => [
                        "label" => "Nama Produk",
                        "rules" => "required|max_length[200]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:200)",
                        ]
                    ],
                    "kode_produk.$i" => [
                        "label" => "Kode Produk",
                        "rules" => "required|max_length[50]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:50)",
                        ]
                    ],
                    "foto_produk.$i" => [
                        "label" => "Foto Produk",
                        "rules" => "required|max_length[50]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:50)",
                        ]
                    ],
                    "tgl_register.$i" => [
                        "label" => "Tanggal registrasi",
                        "rules" => "required|valid_date",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "valid_date" => "{field} tidak valid",
                        ]
                    ],
                ]);

                $error[$i] = [
                    'nama_produk' => $validation->getError("nama_produk.$i"), 'kode_produk' => $validation->getError("kode_produk.$i"), 'foto_produk' => $validation->getError("foto_produk.$i"), 'tgl_register' => $validation->getError("tgl_register.$i"),
                ];

                // Validasi khusus 
                if ($this->KategoriModel->select('id_kategori')->find($this->request->getVar('tbl_kategori')[$i]) == null) {
                    $error[$i]['tbl_kategori'] = 'Kategori tersebut tidak ada';
                }
            }

            // Jika terdapat kesalahan
            if (!$valid) {
                $error["jumlah_data"] = $jumlah_data;
                $view = $error;
            } else {
                // Mengambil data dari request
                $id_produk = $this->request->getVar('id_produk');
                $id_kategorip = $this->request->getVar('id_kategori-p');
                $nama_produk = $this->request->getVar('nama_produk');
                $kode_produk = $this->request->getVar('kode_produk');
                $foto_produk = $this->request->getVar('foto_produk');
                $tgl_register = $this->request->getVar('tgl_register');

                // Perulangan untuk menyiapkan array yang akan diinsert
                for ($i = 0; $i < $jumlah_data; $i++) {
                    $data[$i] = [
                        'id_produk' => $id_produk[$i],
                        'id_kategori-p' => $id_kategorip = [$i],
                        'nama_produk' => htmlspecialchars($nama_produk[$i]),
                        'kode_produk' => htmlspecialchars($kode_produk[$i]),
                        'foto_produk' => htmlspecialchars($foto_produk[$i]),
                        'tgl_register' => htmlspecialchars($tgl_register[$i]),
                    ];
                }

                // Insert banyak ke database
                $this->ProdukModel->updateBatch($data, 'id_produk');

                // Mengembalikan pesan berhasil
                $view = [
                    'success' => "$jumlah_data data tersebut berhasil ditambahkan",
                ];
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
}
