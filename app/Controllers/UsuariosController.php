<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

/**
 * UsuariosController
 * 
 * Maneja todas las operaciones CRUD para usuarios
 * Requiere autenticación de admin para crear/editar/eliminar
 * 
 * @package App\Controllers
 */
class UsuariosController extends Controller
{
    protected $usuarioModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Listar todos los usuarios con paginación
     * GET /usuarios
     */
    public function index()
    {
        try {
            $page = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('perPage') ?? 10;
            $rol = $this->request->getVar('rol') ?? '';

            $builder = $this->usuarioModel->builder();

            if ($rol) {
                $builder->where('rol', $rol);
            }

            $builder->where('estado', 'activo');

            $usuarios = $builder->paginate($perPage);
            $pager = $this->usuarioModel->pager;

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuarios,
                'pagination' => [
                    'current_page' => $pager->getCurrentPage(),
                    'total_pages' => $pager->getPageCount(),
                    'per_page' => $perPage,
                    'total' => $pager->getDetails()['total'],
                ],
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener usuario por ID
     * GET /usuarios/{id}
     */
    public function obtener($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de usuario requerido',
                ])->setStatusCode(400);
            }

            $usuario = $this->usuarioModel->find($id);

            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                ])->setStatusCode(404);
            }

            // No incluir contraseña
            unset($usuario['password']);

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuario,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear nuevo usuario
     * POST /usuarios
     */
    public function crear()
    {
        try {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!$this->usuarioModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validación fallida',
                    'errors' => $this->usuarioModel->errors(),
                ])->setStatusCode(422);
            }

            $usuarioId = $this->usuarioModel->crearUsuario($data);

            if (!$usuarioId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear usuario',
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'user_id' => $usuarioId,
            ])->setStatusCode(201);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar usuario
     * PUT /usuarios/{id}
     */
    public function actualizar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de usuario requerido',
                ])->setStatusCode(400);
            }

            $usuario = $this->usuarioModel->find($id);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                ])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            // No permitir cambio de email (debe ser único)
            if (isset($data['email']) && $data['email'] !== $usuario['email']) {
                unset($data['email']);
            }

            // No permitir cambio de contraseña por este endpoint
            if (isset($data['password'])) {
                unset($data['password']);
            }

            if ($this->usuarioModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar usuario',
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Cambiar contraseña de usuario
     * POST /usuarios/{id}/cambiar-password
     */
    public function cambiarPassword($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de usuario requerido',
                ])->setStatusCode(400);
            }

            $usuario = $this->usuarioModel->find($id);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                ])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!isset($data['password_actual']) || !isset($data['password_nueva'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contraseña actual y nueva requeridas',
                ])->setStatusCode(400);
            }

            // Verificar contraseña actual
            if (!password_verify($data['password_actual'], $usuario['password'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contraseña actual incorrecta',
                ])->setStatusCode(401);
            }

            if ($this->usuarioModel->cambiarPassword($id, $data['password_nueva'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contraseña actualizada exitosamente',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cambiar contraseña',
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Desactivar usuario
     * DELETE /usuarios/{id}
     */
    public function eliminar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de usuario requerido',
                ])->setStatusCode(400);
            }

            $usuario = $this->usuarioModel->find($id);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                ])->setStatusCode(404);
            }

            // Usar soft delete (cambiar estado a inactivo)
            if ($this->usuarioModel->desactivar($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Usuario desactivado exitosamente',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al desactivar usuario',
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener usuarios por barrio
     * GET /usuarios/barrio/{barrio}
     */
    public function porBarrio($barrio = null)
    {
        try {
            if (!$barrio) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Barrio requerido',
                ])->setStatusCode(400);
            }

            $usuarios = $this->usuarioModel->getByBarrio($barrio);

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuarios,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener estadísticas de usuarios
     * GET /usuarios/estadisticas
     */
    public function estadisticas()
    {
        try {
            $stats = [
                'total' => $this->usuarioModel->contarTotal(),
                'ciudadanos' => $this->usuarioModel->contarPorRol('ciudadano'),
                'autoridades' => $this->usuarioModel->contarPorRol('autoridad'),
                'administradores' => $this->usuarioModel->contarPorRol('admin'),
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
