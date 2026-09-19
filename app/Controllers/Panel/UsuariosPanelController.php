<?php

namespace App\Controllers\Panel;

use App\Models\UsuarioModel;

/**
 * UsuariosPanelController
 *
 * CRUD completo de usuarios para el panel. Solo accesible por admin
 * (ver grupo de rutas en app/Config/Routes.php).
 *
 * @package App\Controllers\Panel
 */
class UsuariosPanelController extends BasePanelController
{
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        return view('panel/usuarios/index', [
            'title' => 'Usuarios',
            'usuarios' => $this->usuarioModel
                ->select('id, nombre, email, rol, barrio, estado, created_at')
                ->orderBy('id', 'DESC')
                ->findAll(200),
        ]);
    }

    public function editar(int $id)
    {
        $usuario = $this->usuarioModel->select('id, nombre, email, rol, barrio, estado')->find($id);

        if (! $usuario) {
            return $this->conExito('/panel/usuarios', 'Ese usuario no existe.');
        }

        return view('panel/usuarios/editar', [
            'title' => 'Editar usuario',
            'item' => $usuario,
        ]);
    }

    public function crear()
    {
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'rol' => (string) $this->request->getPost('rol'),
            'barrio' => trim((string) $this->request->getPost('barrio')),
            'estado' => (string) $this->request->getPost('estado'),
        ];

        if (! $this->usuarioModel->crearUsuario($data)) {
            return $this->conError('/panel/usuarios', $this->usuarioModel->errors());
        }

        return $this->conExito('/panel/usuarios', 'Usuario creado correctamente.');
    }

    public function actualizar(int $id)
    {
        if (! $this->usuarioModel->find($id)) {
            return $this->conExito('/panel/usuarios', 'Ese usuario no existe.');
        }

        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => trim((string) $this->request->getPost('email')),
            'rol' => (string) $this->request->getPost('rol'),
            'barrio' => trim((string) $this->request->getPost('barrio')),
            'estado' => (string) $this->request->getPost('estado'),
        ];

        // La contraseña no se toca desde este formulario. El placeholder
        // {id} de is_unique NO se resuelve solo: CI4 solo lo llena si 'id'
        // viene en el array de datos (no es el caso en un update normal),
        // así que se reemplaza a mano por el id real para excluir al propio
        // registro de la verificación de unicidad.
        $rules = $this->usuarioModel->getValidationRules();
        unset($rules['password']);
        $rules['email'] = "required|valid_email|is_unique[usuarios.email,id,{$id}]";

        if (! $this->usuarioModel->setValidationRules($rules)->update($id, $data)) {
            return $this->conError('/panel/usuarios/' . $id . '/editar', $this->usuarioModel->errors());
        }

        return $this->conExito('/panel/usuarios', 'Usuario actualizado correctamente.');
    }

    public function eliminar(int $id)
    {
        if (! $this->usuarioModel->find($id)) {
            return $this->conExito('/panel/usuarios', 'Ese usuario no existe.');
        }

        $this->usuarioModel->desactivar($id);

        return $this->conExito('/panel/usuarios', 'Usuario desactivado correctamente.');
    }
}
