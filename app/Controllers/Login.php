<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Login extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        return view('/pages/login_view');
    }
    public function process()
    {
        $users = new UserModel();
        $username = $this->request->getVar('username');
        $dataUser = $users->where([
            'nama_user' => $username,
        ])->first();
        if ($dataUser) {
            session()->set([
                'nama_user' => $dataUser['nama_user'],
                'logged_in' => TRUE,
            ]);
            return redirect()->to('/');
        } else {
            session()->setFlashdata('error', 'Nama Salah');
            return redirect()->back();
        }
    }
    function logout()
    {
        session()->destroy();
        return redirect()->to('/login/index');
    }
}
