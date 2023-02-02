<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
    use RequestTrait;
    public function index()
    {
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new UserModel();
        $user = $model->where("email", $this->request->getVar('email'))->first();
        if (!$user) return $this->failNotFound('Email Not Found');

        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if (!$verify) return $this->fail('Wrong Password');
        // jwt
        $key = getenv('TOKEN_SECRET');
        $payload = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "uid" => $user['id'],
            "email" => $user['email']
        );

        $token = JWT::encode($payload, $key, 'HS256');
        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];
        return $this->respond($response);
    }
}
