<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 *
 * Exige sesión iniciada. Con argumentos (p. ej. 'auth:admin,autoridad')
 * además exige que el rol del usuario esté en la lista permitida.
 *
 * @package App\Filters
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $usuario = session()->get('usuario');

        // La sesión trae un usuario, pero puede que ya no exista (o esté
        // inactivo) en la base: por ejemplo si se lo borró/desactivó
        // mientras tenía la sesión abierta. Sin esto, cualquier acción que
        // dependa de ese user_id (votar, crear un reporte) rompe más abajo
        // con un error de foreign key en vez de pedirle que vuelva a entrar.
        if ($usuario && ! (new UsuarioModel())->where('estado', 'activo')->find($usuario['id'])) {
            session()->destroy();
            $usuario = null;
        }

        if (! $usuario) {
            if ($request->isAJAX()) {
                return response()->setJSON([
                    'success' => false,
                    'message' => 'Usuario no autenticado',
                ])->setStatusCode(401);
            }

            session()->setFlashdata('errors', ['auth' => 'Inicia sesión para continuar.']);

            return redirect()->to('/login')->withCookies();
        }

        $rolesPermitidos = array_filter((array) $arguments);

        if ($rolesPermitidos !== [] && ! in_array($usuario['rol'], $rolesPermitidos, true)) {
            if ($request->isAJAX()) {
                return response()->setJSON([
                    'success' => false,
                    'message' => 'No tienes permisos para acceder a este recurso',
                ])->setStatusCode(403);
            }

            session()->setFlashdata('errors', ['auth' => 'No tienes permisos para acceder a esa sección.']);

            return redirect()->to('/');
        }

        $request->usuario = $usuario;

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No-op
    }
}
