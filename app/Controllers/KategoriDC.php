<?php

namespace App\Controllers;

use App\Models\KategoriServersideModel;
use Config\Services;

class KategoriDC extends BaseController
{

    public function getAllKategori()
    {
        if ($this->request->isAJAX()) {
            $view = [
                'data' => view('data_view/v_datakategori'),
            ];

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function kategoriServerside()
    {
        if ($this->request->isAJAX()) {
            $request = Services::request();
            $KategoriServersideModel = new KategoriServersideModel($request);

            #sebelumnya -if bawah ini- $request->getMethod(true) == 'POST'
            if ($request->getPost()) {
                $lists = $KategoriServersideModel->get_datatables();
                $data = [];
                $no = $request->getPost("start");
                foreach ($lists as $list) {
                    $no++;
                    $row = [];

                    $tomboledit = "<button type=\"button\" class=\"btn btn-primary btn-sm my-1\" onclick=\"editData(" . $list['id_kategori'] . ")\"><i class=\"fas fa-edit\"></i> </button>";

                    $tombolhapus = "<button type=\"button\" class=\"btn btn-danger btn-sm my-1\" onclick=\"deleteData(" . $list['id_kategori'] . ")\"><i class=\"fas fa-trash-alt\"></i> </button>";

                    $row[] = "<input type=\"checkbox\" class=\"checkbox_kategori\" value=\"" . $list['id_kategori'] . "\">";
                    $row[] = $no;
                    $row[] = $list['nama_kategori'];
                    $row[] = $tomboledit . " " . $tombolhapus;

                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->getPost('draw'),
                    "recordsTotal" => $KategoriServersideModel->count_all(),
                    "recordsFiltered" => $KategoriServersideModel->count_filtered(),
                    "data" => $data
                ];
                echo json_encode($output);
            }
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function addKategori()
    {
        if ($this->request->isAJAX()) {
            $view = [
                'data' => view('data_view/v_tambahdata_kategori')
            ];

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function saveAddKategori()
    {
        if ($this->request->isAJAX()) {
            $jumlah_data = count($this->request->getVar('nama_kategori'));

            $validation = \Config\Services::validation();

            // Perulangan untuk validasi
            for ($i = 0; $i < $jumlah_data; $i++) {
                $valid = $this->validate([
                    "nama_kategori.$i" => [
                        "label" => "Nama Kategori",
                        "rules" => "required|max_length[200]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:200)",
                        ]
                    ],
                ]);

                // Membuat pesan error dalam bentuk array
                $error[$i] = [
                    'nama_kategori' => $validation->getError("nama_kategori.$i"),
                ];
            }
            // Jika terdapat kesalahan
            if (!$valid) {
                $error["jumlah_data"] = $jumlah_data;
                $view = $error;
            } else {
                // Mengambil data dari request
                $nama_kategori = $this->request->getVar('nama_kategori');

                // Perulangan untuk menyiapkan array yang akan diinsert
                for ($i = 0; $i < $jumlah_data; $i++) {
                    $data[$i] = [
                        'nama_kategori' => htmlspecialchars($nama_kategori[$i]),
                    ];
                }

                // Insert banyak ke database
                $this->KategoriModel->insertBatch($data);

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

    public function deleteKategori()
    {
        if ($this->request->isAJAX()) {
            $id_kategori = $this->request->getVar('id_kategori');
            $jmldata = count($id_kategori);
            $jmlditemukan = count($this->KategoriModel->select('id_kategori')->find($id_kategori));

            $this->KategoriModel->delete($id_kategori);

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

    public function editKategori()
    {
        if ($this->request->isAJAX()) {

            $data['tbl_kategori'] = $this->KategoriModel->select('id_kategori, nama_kategori')->find($this->request->getVar('id_kategori'));
            // Jika data yang akan dihapus tidak ditemukan
            if ($data['tbl_kategori'] == null) {
                $view = [
                    'error' => "Data tersebut tidak ditemukan",
                ];
            } else {
                $view = [
                    'data' => view('data_view/v_editdata_kategori', $data),
                ];
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function saveEditKategori()
    {
        if ($this->request->isAJAX()) {
            $jumlah_data = count($this->request->getVar('id_kategori'));

            $validation = \Config\Services::validation();

            // Perulangan untuk validasi
            for ($i = 0; $i < $jumlah_data; $i++) {
                $valid = $this->validate([
                    "nama_kategori.$i" => [
                        "label" => "Nama kategori",
                        "rules" => "required|max_length[200]",
                        "errors" => [
                            "required" => "{field} tidak boleh kosong",
                            "max_length" => "{field} terlalu panjang (max:200)",
                        ]
                    ],
                ]);

                // Membuat pesan error dalam bentuk array
                $error[$i] = [
                    'nama_kategori' => $validation->getError("nama_kategori.$i"),
                ];
            }

            // Jika terdapat kesalahan
            if (!$valid) {
                $error["jumlah_data"] = $jumlah_data;
                $view = $error;
            } else {
                // Mengambil data dari request
                $id_kategori = $this->request->getVar('id_kategori');
                $nama_kategori = $this->request->getVar('nama_kategori');

                // Perulangan untuk menyiapkan array yang akan diupdate
                for ($i = 0; $i < $jumlah_data; $i++) {
                    $data[$i] = [
                        'id_kategori' => $id_kategori[$i],
                        'nama_kategori' => htmlspecialchars($nama_kategori[$i]),
                    ];
                }

                // Update banyak ke database
                $this->KategoriModel->updateBatch($data, 'id_kategori');

                // Mengembalikan pesan berhasil
                $view = [
                    'success' => "$jumlah_data data tersebut berhasil diubah",
                ];
            }

            echo json_encode($view);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
}
