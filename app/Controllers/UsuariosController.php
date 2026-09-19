<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

/**
 * UsuariosController
 *
 * Maneja todas las operaciones CRUD para usuarios
 * Requiere autenticación de admin para crear/editar/eliminar
 *
 * @package App\Controllers
 */
class UsuariosController extends BaseApiController
{
    protected $usuarioModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Listar todos los usuarios con paginación
     * GET /api/usuarios
     */
    public function index()
    {
        return $this->attempt(function () {
            $perPage = (int) ($this->request->getVar('perPage') ?? 10);
            $rol = $this->request->getVar('rol') ?? '';

            // paginate() vive en el Model, no en el Builder crudo: encadenar
            // sobre $this->usuarioModel (no sobre ->builder()) es lo que
            // permite llamarlo después.
            // select() explícito: nunca devolver el hash de password en un listado.
            $this->usuarioModel
                ->select('id, nombre, email, rol, barrio, estado, created_at, updated_at')
                ->where('estado', 'activo');

            if ($rol) {
                $this->usuarioModel->where('rol', $rol);
            }

            $usuarios = $this->usuarioModel->paginate($perPage);
            $pager = $this->usuarioModel->pager;

            return $this->ok($usuarios, '', 200, [
                'current_page' => $pager->getCurrentPage(),
                'total_pages' => $pager->getPageCount(),
                'per_page' => $perPage,
                'total' => $pager->getDetails()['total'],
            ]);
        });
    }

    /**
     * Obtener usuario por ID
     * GET /api/usuarios/{id}
     */
    public function obtener($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID de usuario requerido', 400);
            }

            $usuario = $this->usuarioModel->find($id);

            if (! $usuario) {
                return $this->fail('Usuario no encontrado', 404);
            }

            unset($usuario['password']);

            return $this->ok($usuario);
        });
    }

    /**
     * Crear nuevo usuario
     * POST /api/usuarios
     */
    public function crear()
    {
        return $this->attempt(function () {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! $this->usuarioModel->validate($data)) {
                return $this->fail('Validación fallida', 422, $this->usuarioModel->errors());
            }

            $usuarioId = $this->usuarioModel->crearUsuario($data);

            if (! $usuarioId) {
                return $this->fail('Error al crear usuario', 500);
            }

            return $this->ok(['user_id' => $usuarioId], 'Usuario creado exitosamente', 201);
        });
    }

    /**
     * Actualizar usuario
     * PUT /api/usuarios/{id}
     */
    public function actualizar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID de usuario requerido', 400);
            }

            $usuario = $this->usuarioModel->find($id);

            if (! $usuario) {
                return $this->fail('Usuario no encontrado', 404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            // No permitir cambio de email ni contraseña por este endpoint
            unset($data['email'], $data['password']);

            if ($this->usuarioModel->update($id, $data)) {
                return $this->ok(null, 'Usuario actualizado exitosamente');
            }

            return $this->fail('Error al actualizar usuario', 500);
        });
    }

    /**
     * Cambiar contraseña de usuario
     * POST /api/usuarios/{id}/cambiar-password
     */
    public function cambiarPassword($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID de usuario requerido', 400);
            }

            $usuario = $this->usuarioModel->find($id);

            if (! $usuario) {
                return $this->fail('Usuario no encontrado', 404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! isset($data['password_actual'], $data['password_nueva'])) {
                return $this->fail('Contraseña actual y nueva requeridas', 400);
            }

            if (! password_verify($data['password_actual'], $usuario['password'])) {
                return $this->fail('Contraseña actual incorrecta', 401);
            }

            if ($this->usuarioModel->cambiarPassword((int) $id, $data['password_nueva'])) {
                return $this->ok(null, 'Contraseña actualizada exitosamente');
            }

            return $this->fail('Error al cambiar contraseña', 500);
        });
    }

    /**
     * Desactivar usuario (soft delete lógico vía estado)
     * DELETE /api/usuarios/{id}
     */
    public function eliminar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID de usuario requerido', 400);
            }

            if (! $this->usuarioModel->find($id)) {
                return $this->fail('Usuario no encontrado', 404);
            }

            if ($this->usuarioModel->desactivar((int) $id)) {
                return $this->ok(null, 'Usuario desactivado exitosamente');
            }

            return $this->fail('Error al desactivar usuario', 500);
        });
    }

    /**
     * Obtener usuarios por barrio
     * GET /api/usuarios/barrio/{barrio}
     */
    public function porBarrio($barrio = null)
    {
        return $this->attempt(function () use ($barrio) {
            if (! $barrio) {
                return $this->fail('Barrio requerido', 400);
            }

            return $this->ok($this->usuarioModel->getByBarrio($barrio));
        });
    }

    /**
     * Obtener estadísticas de usuarios
     * GET /api/usuarios/estadisticas
     */
    public function estadisticas()
    {
        return $this->attempt(function () {
            return $this->ok([
                'total' => $this->usuarioModel->contarTotal(),
                'ciudadanos' => $this->usuarioModel->contarPorRol('ciudadano'),
                'autoridades' => $this->usuarioModel->contarPorRol('autoridad'),
                'administradores' => $this->usuarioModel->contarPorRol('admin'),
            ]);
        });
    }
}
