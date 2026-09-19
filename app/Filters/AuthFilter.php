<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 * 
 * Filtro para validar que el usuario esté autenticado
 * Se aplica a rutas protegidas
 * 
 * @package App\Filters
 */
class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not change the request or response.
     *
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     *
     * @return RequestInterface|ResponseInterface|string|null
     */
    public function before(RequestInterface $request, $response = null)
    {
        // Obtener usuario de sesión
        $usuario = session()->get('usuario');

        if (!$usuario) {
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return response()
                    ->setJSON([
                        'success' => false,
                        'message' => 'Usuario no autenticado',
                    ])
                    ->setStatusCode(401);
            }

            // Si es una petición HTTP normal, redirigir a login
            return redirect()->to('/auth/login');
        }

        // Guardar usuario en $request para usarlo en controladores
        $request->usuario = $usuario;

        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. If this filter returns anything, it
     * should be the Response object itself.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // No-op
    }
}
