<?php

namespace App\Controllers;

use App\Models\ReporteModel;
use CodeIgniter\Controller;

/**
 * ReportesController
 * 
 * Maneja CRUD de reportes de problemas
 * Cualquier usuario autenticado puede crear reportes
 * 
 * @package App\Controllers
 */
class ReportesController extends Controller
{
    protected $reporteModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->reporteModel = new ReporteModel();
    }

    /**
     * Listar reportes con paginación y filtros
     * GET /reportes
     */
    public function index()
    {
        try {
            $page = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('perPage') ?? 10;
            $estado = $this->request->getVar('estado') ?? '';

            $reportes = $this->reporteModel->getReportesConDetalles($perPage, $page, $estado);

            return $this->response->setJSON([
                'success' => true,
                'data' => $reportes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener reporte por ID
     * GET /reportes/{id}
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

            $reporte = $this->reporteModel->select('reportes.*, usuarios.nombre, usuarios.email, usuarios.barrio, categorias.nombre as categoria_nombre')
                                          ->join('usuarios', 'usuarios.id = reportes.user_id')
                                          ->join('categorias', 'categorias.id = reportes.categoria_id')
                                          ->where('reportes.id', $id)
                                          ->first();

            if (!$reporte) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Reporte no encontrado',
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $reporte,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear nuevo reporte
     * POST /reportes
     */
    public function crear()
    {
        try {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!$this->reporteModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validación fallida',
                    'errors' => $this->reporteModel->errors(),
                ])->setStatusCode(422);
            }

            if ($this->reporteModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reporte creado exitosamente',
                ])->setStatusCode(201);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear reporte',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar reporte
     * PUT /reportes/{id}
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

            if (!$this->reporteModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Reporte no encontrado',
                ])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->reporteModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reporte actualizado exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar reporte',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Cambiar estado del reporte
     * PATCH /reportes/{id}/estado
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

            if ($this->reporteModel->cambiarEstado($id, $data['estado'])) {
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
     * Eliminar reporte (soft delete)
     * DELETE /reportes/{id}
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

            if (!$this->reporteModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Reporte no encontrado',
                ])->setStatusCode(404);
            }

            if ($this->reporteModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reporte eliminado exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar reporte',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener reportes por categoría
     * GET /reportes/categoria/{categoriaId}
     */
    public function porCategoria($categoriaId = null)
    {
        try {
            if (!$categoriaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de categoría requerido',
                ])->setStatusCode(400);
            }

            $perPage = $this->request->getVar('perPage') ?? 10;
            $reportes = $this->reporteModel->getReportesPorCategoria($categoriaId, $perPage);

            return $this->response->setJSON([
                'success' => true,
                'data' => $reportes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener reportes por usuario
     * GET /reportes/usuario/{userId}
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

            $reportes = $this->reporteModel->getReportesPorUsuario($userId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $reportes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener reportes con filtros
     * POST /reportes/filtro
     */
    public function filtro()
    {
        try {
            $filtros = $this->request->getJSON(true) ?? $this->request->getPost();
            $perPage = $this->request->getVar('perPage') ?? 10;

            $reportes = $this->reporteModel->getFiltrados($filtros, $perPage);

            return $this->response->setJSON([
                'success' => true,
                'data' => $reportes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener reportes más votados
     * GET /reportes/populares/{limit}
     */
    public function populares($limit = 5)
    {
        try {
            $reportes = $this->reporteModel->getMasVotados((int)$limit);

            return $this->response->setJSON([
                'success' => true,
                'data' => $reportes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener estadísticas de reportes
     * GET /reportes/estadisticas
     */
    public function estadisticas()
    {
        try {
            $stats = $this->reporteModel->getEstadisticas();

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
