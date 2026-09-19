<?php

namespace App\Controllers\Panel;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * BasePanelController
 *
 * Controladores del panel administrativo (autoridad/admin). Centraliza
 * los helpers de redirección con flash data que se repetían idénticos
 * en cada acción crear/editar/eliminar.
 *
 * @package App\Controllers\Panel
 */
abstract class BasePanelController extends Controller
{
    protected function conExito(string $ruta, string $mensaje): RedirectResponse
    {
        return redirect()->to($ruta)->with('success', $mensaje);
    }

    /**
     * @param array<string, string> $errores
     */
    protected function conError(string $ruta, array $errores): RedirectResponse
    {
        return redirect()->to($ruta)->withInput()->with('errors', $errores);
    }
}
