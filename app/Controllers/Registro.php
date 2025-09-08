<?
namespace App\Controllers;
use App\Models\UsuarioModel;

class Registro extends BaseController
{
    public function index()
    {
        return view('registro');
    }

    public function save()
    {
        $usuarioModel = new UsuarioModel();

        $usuarioModel->save([
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/login')->with('success', 'Cuenta creada, inicia sesión');
    }
}
