<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

/**
 * AuthController
 *
 * Login, registro público (como ciudadano) y logout basados en sesión.
 *
 * @package App\Controllers
 */
class AuthController extends Controller
{
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login()
    {
        if (session()->get('usuario')) {
            return redirect()->to('/');
        }

        return view('auth/login', ['title' => 'Iniciar sesión']);
    }

    public function procesarLogin()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        // Máximo 5 intentos por minuto por IP+email para frenar fuerza bruta.
        $throttler = service('throttler');
        if ($throttler->check(md5('login' . $email . $this->request->getIPAddress()), 5, 60) === false) {
            return redirect()->to('/login')->with('errors', ['login' => 'Demasiados intentos. Espera un minuto e intenta de nuevo.']);
        }

        $usuario = $this->usuarioModel->validarCredenciales($email, $password);

        if (! $usuario) {
            return redirect()->to('/login')->withInput()->with('errors', ['login' => 'Email o contraseña incorrectos.']);
        }

        unset($usuario['password']);
        session()->set('usuario', $usuario);
        session()->regenerate();

        return redirect()->to($usuario['rol'] === 'ciudadano' ? '/' : '/panel')
            ->with('success', 'Bienvenido/a, ' . $usuario['nombre'] . '.');
    }

    public function registro()
    {
        if (session()->get('usuario')) {
            return redirect()->to('/');
        }

        return view('auth/registro', ['title' => 'Crear cuenta']);
    }

    public function procesarRegistro()
    {
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'rol' => 'ciudadano',
            'barrio' => trim((string) $this->request->getPost('barrio')),
            'estado' => 'activo',
        ];

        if (! $this->usuarioModel->crearUsuario($data)) {
            return redirect()->to('/registro')->withInput()->with('errors', $this->usuarioModel->errors());
        }

        $usuario = $this->usuarioModel->getByEmail($data['email']);
        unset($usuario['password']);
        session()->set('usuario', $usuario);

        return redirect()->to('/')->with('success', '¡Cuenta creada! Ya puedes reportar problemas y votar propuestas.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/')->with('success', 'Sesión cerrada correctamente.');
    }
}
