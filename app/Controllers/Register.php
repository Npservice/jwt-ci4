<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;

class Register extends ResourceController
{

    use RequestTrait;
    public function index()
    {
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confpassword' => 'matches[password]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $data = [
            'email'     => $this->request->getVar('email'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT)
        ];
        $model = new UserModel();
        $registered = $model->save($data);
        $this->respondCreated($registered);
    }
}
