<?php

namespace App\Controllers;

use App\Models\ReporteModel;

/**
 * ReportesController
 *
 * Maneja CRUD de reportes de problemas
 * Cualquier usuario autenticado puede crear reportes
 *
 * @package App\Controllers
 */
class ReportesController extends BaseApiController
{
    protected $reporteModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->reporteModel = new ReporteModel();
    }

    /**
     * Listar reportes con paginación y filtros
     * GET /api/reportes
     */
    public function index()
    {
        return $this->attempt(function () {
            $page = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('perPage') ?? 10;
            $estado = $this->request->getVar('estado') ?? '';

            return $this->ok($this->reporteModel->getReportesConDetalles((int) $perPage, (int) $page, (string) $estado));
        });
    }

    /**
     * Obtener reporte por ID
     * GET /api/reportes/{id}
     */
    public function obtener($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            $reporte = $this->reporteModel->select('reportes.*, usuarios.nombre, usuarios.email, usuarios.barrio, categorias.nombre as categoria_nombre')
                ->join('usuarios', 'usuarios.id = reportes.user_id')
                ->join('categorias', 'categorias.id = reportes.categoria_id')
                ->where('reportes.id', $id)
                ->first();

            if (! $reporte) {
                return $this->fail('Reporte no encontrado', 404);
            }

            return $this->ok($reporte);
        });
    }

    /**
     * Crear nuevo reporte
     * POST /api/reportes
     */
    public function crear()
    {
        return $this->attempt(function () {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! $this->reporteModel->validate($data)) {
                return $this->fail('Validación fallida', 422, $this->reporteModel->errors());
            }

            if ($this->reporteModel->insert($data)) {
                return $this->ok(null, 'Reporte creado exitosamente', 201);
            }

            return $this->fail('Error al crear reporte', 500);
        });
    }

    /**
     * Actualizar reporte
     * PUT /api/reportes/{id}
     */
    public function actualizar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->reporteModel->find($id)) {
                return $this->fail('Reporte no encontrado', 404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->reporteModel->update($id, $data)) {
                return $this->ok(null, 'Reporte actualizado exitosamente');
            }

            return $this->fail('Error al actualizar reporte', 500);
        });
    }

    /**
     * Cambiar estado del reporte
     * PATCH /api/reportes/{id}/estado
     */
    public function cambiarEstado($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! isset($data['estado'])) {
                return $this->fail('Estado requerido', 400);
            }

            if ($this->reporteModel->cambiarEstado((int) $id, $data['estado'])) {
                return $this->ok(null, 'Estado actualizado exitosamente');
            }

            return $this->fail('Error al cambiar estado', 500);
        });
    }

    /**
     * Eliminar reporte (soft delete)
     * DELETE /api/reportes/{id}
     */
    public function eliminar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->reporteModel->find($id)) {
                return $this->fail('Reporte no encontrado', 404);
            }

            if ($this->reporteModel->delete($id)) {
                return $this->ok(null, 'Reporte eliminado exitosamente');
            }

            return $this->fail('Error al eliminar reporte', 500);
        });
    }

    /**
     * Obtener reportes por categoría
     * GET /api/reportes/categoria/{categoriaId}
     */
    public function porCategoria($categoriaId = null)
    {
        return $this->attempt(function () use ($categoriaId) {
            if (! $categoriaId) {
                return $this->fail('ID de categoría requerido', 400);
            }

            $perPage = $this->request->getVar('perPage') ?? 10;

            return $this->ok($this->reporteModel->getReportesPorCategoria((int) $categoriaId, (int) $perPage));
        });
    }

    /**
     * Obtener reportes por usuario
     * GET /api/reportes/usuario/{userId}
     */
    public function porUsuario($userId = null)
    {
        return $this->attempt(function () use ($userId) {
            if (! $userId) {
                return $this->fail('ID de usuario requerido', 400);
            }

            return $this->ok($this->reporteModel->getReportesPorUsuario((int) $userId));
        });
    }

    /**
     * Obtener reportes con filtros
     * POST /api/reportes/filtro
     */
    public function filtro()
    {
        return $this->attempt(function () {
            $filtros = $this->request->getJSON(true) ?? $this->request->getPost();
            $perPage = $this->request->getVar('perPage') ?? 10;

            return $this->ok($this->reporteModel->getFiltrados((array) $filtros, (int) $perPage));
        });
    }

    /**
     * Obtener reportes más votados
     * GET /api/reportes/populares/{limit}
     */
    public function populares($limit = 5)
    {
        return $this->attempt(function () use ($limit) {
            return $this->ok($this->reporteModel->getMasVotados((int) $limit));
        });
    }

    /**
     * Obtener estadísticas de reportes
     * GET /api/reportes/estadisticas
     */
    public function estadisticas()
    {
        return $this->attempt(function () {
            return $this->ok($this->reporteModel->getEstadisticas());
        });
    }
}
