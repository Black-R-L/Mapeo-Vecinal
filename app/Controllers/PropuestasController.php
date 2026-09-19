<?php

namespace App\Controllers;

use App\Models\PropuestaModel;
use CodeIgniter\Controller;

/**
 * PropuestasController
 * 
 * Maneja CRUD de propuestas de mejoras
 * Usuarios pueden crear y votar en propuestas
 * 
 * @package App\Controllers
 */
class PropuestasController extends Controller
{
    protected $propuestaModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->propuestaModel = new PropuestaModel();
    }

    /**
     * Listar propuestas con paginación
     * GET /propuestas
     */
    public function index()
    {
        try {
            $page = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('perPage') ?? 10;
            $estado = $this->request->getVar('estado') ?? '';

            $propuestas = $this->propuestaModel->getPropuestasConDetalles($perPage, $page, $estado);

            return $this->response->setJSON([
                'success' => true,
                'data' => $propuestas,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener propuesta por ID
     * GET /propuestas/{id}
     */
    public function obtener($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            $propuesta = $this->propuestaModel->select('propuestas.*, usuarios.nombre, usuarios.email, categorias.nombre as categoria_nombre')
                                              ->join('usuarios', 'usuarios.id = propuestas.user_id')
                                              ->join('categorias', 'categorias.id = propuestas.categoria_id')
                                              ->where('propuestas.id', $id)
                                              ->first();

            if (!$propuesta) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Propuesta no encontrada',
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $propuesta,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear nueva propuesta
     * POST /propuestas
     */
    public function crear()
    {
        try {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!$this->propuestaModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validación fallida',
                    'errors' => $this->propuestaModel->errors(),
                ])->setStatusCode(422);
            }

            if ($this->propuestaModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Propuesta creada exitosamente',
                ])->setStatusCode(201);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear propuesta',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar propuesta
     * PUT /propuestas/{id}
     */
    public function actualizar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            if (!$this->propuestaModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Propuesta no encontrada',
                ])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->propuestaModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Propuesta actualizada exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar propuesta',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Cambiar estado de propuesta
     * PATCH /propuestas/{id}/estado
     */
    public function cambiarEstado($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!isset($data['estado'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado requerido',
                ])->setStatusCode(400);
            }

            if ($this->propuestaModel->cambiarEstado($id, $data['estado'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar estado',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Activar votación para propuesta
     * PATCH /propuestas/{id}/activar-votacion
     */
    public function activarVotacion($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            if ($this->propuestaModel->activarVotacion($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Votación activada exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al activar votación',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar propuesta (soft delete)
     * DELETE /propuestas/{id}
     */
    public function eliminar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            if (!$this->propuestaModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Propuesta no encontrada',
                ])->setStatusCode(404);
            }

            if ($this->propuestaModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Propuesta eliminada exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar propuesta',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener propuestas en votación
     * GET /propuestas/votacion
     */
    public function enVotacion()
    {
        try {
            $perPage = $this->request->getVar('perPage') ?? 10;
            $propuestas = $this->propuestaModel->getEnVotacion($perPage);

            return $this->response->setJSON([
                'success' => true,
                'data' => $propuestas,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener propuestas más votadas
     * GET /propuestas/populares/{limit}
     */
    public function populares($limit = 10)
    {
        try {
            $propuestas = $this->propuestaModel->getMasVotadas((int)$limit);

            return $this->response->setJSON([
                'success' => true,
                'data' => $propuestas,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener propuestas por usuario
     * GET /propuestas/usuario/{userId}
     */
    public function porUsuario($userId = null)
    {
        try {
            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de usuario requerido',
                ])->setStatusCode(400);
            }

            $propuestas = $this->propuestaModel->getPropuestasPorUsuario($userId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $propuestas,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener estadísticas de propuestas
     * GET /propuestas/estadisticas
     */
    public function estadisticas()
    {
        try {
            $stats = $this->propuestaModel->getEstadisticas();

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
