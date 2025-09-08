<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    public function login()
    {
        return view('pages/usuarios/login');
    }

    public function login_post()
    {
        $session = session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->verificarUsuario($email, $password);

        if ($usuario) {
            $session->set([
                'usuario_id' => $usuario['id'],
                'usuario_nombre' => $usuario['nombre'],
                'logged_in' => true
            ]);
            return redirect()->to('/carrito')->with('success', 'Bienvenido, '.$usuario['nombre']);
        } else {
            return redirect()->back()->with('mensaje', 'Email o contraseña incorrectos.');
        }
    }

    public function registro()
    {
        return view('pages/usuarios/registro');
    }

    public function registro_post()
    {
        $session = session();
        $nombre = $this->request->getPost('nombre');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');

        if ($password !== $password_confirm) {
            return redirect()->back()->with('mensaje', 'Las contraseñas no coinciden.');
        }

        $usuarioModel = new UsuarioModel();

        if ($usuarioModel->where('email', $email)->first()) {
            return redirect()->back()->with('mensaje', 'El email ya está registrado.');
        }

        $usuarioModel->insert([
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/usuarios/login')->with('success', 'Registro exitoso. Iniciá sesión.');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url())->with('success', 'Has cerrado sesión.');
    }

}
