<?php

namespace App\Controllers;

use App\Models\PropuestaModel;

/**
 * PropuestasController
 *
 * Maneja CRUD de propuestas de mejoras
 * Usuarios pueden crear y votar en propuestas
 *
 * @package App\Controllers
 */
class PropuestasController extends BaseApiController
{
    protected $propuestaModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->propuestaModel = new PropuestaModel();
    }

    /**
     * Listar propuestas con paginación
     * GET /api/propuestas
     */
    public function index()
    {
        return $this->attempt(function () {
            $page = $this->request->getVar('page') ?? 1;
            $perPage = $this->request->getVar('perPage') ?? 10;
            $estado = $this->request->getVar('estado') ?? '';

            return $this->ok($this->propuestaModel->getPropuestasConDetalles((int) $perPage, (int) $page, (string) $estado));
        });
    }

    /**
     * Obtener propuesta por ID
     * GET /api/propuestas/{id}
     */
    public function obtener($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            $propuesta = $this->propuestaModel->select('propuestas.*, usuarios.nombre, usuarios.email, categorias.nombre as categoria_nombre')
                ->join('usuarios', 'usuarios.id = propuestas.user_id')
                ->join('categorias', 'categorias.id = propuestas.categoria_id')
                ->where('propuestas.id', $id)
                ->first();

            if (! $propuesta) {
                return $this->fail('Propuesta no encontrada', 404);
            }

            return $this->ok($propuesta);
        });
    }

    /**
     * Crear nueva propuesta
     * POST /api/propuestas
     */
    public function crear()
    {
        return $this->attempt(function () {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! $this->propuestaModel->validate($data)) {
                return $this->fail('Validación fallida', 422, $this->propuestaModel->errors());
            }

            if ($this->propuestaModel->insert($data)) {
                return $this->ok(null, 'Propuesta creada exitosamente', 201);
            }

            return $this->fail('Error al crear propuesta', 500);
        });
    }

    /**
     * Actualizar propuesta
     * PUT /api/propuestas/{id}
     */
    public function actualizar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->propuestaModel->find($id)) {
                return $this->fail('Propuesta no encontrada', 404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->propuestaModel->update($id, $data)) {
                return $this->ok(null, 'Propuesta actualizada exitosamente');
            }

            return $this->fail('Error al actualizar propuesta', 500);
        });
    }

    /**
     * Cambiar estado de propuesta
     * PATCH /api/propuestas/{id}/estado
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

            if ($this->propuestaModel->cambiarEstado((int) $id, $data['estado'])) {
                return $this->ok(null, 'Estado actualizado exitosamente');
            }

            return $this->fail('Error al cambiar estado', 500);
        });
    }

    /**
     * Activar votación para propuesta
     * PATCH /api/propuestas/{id}/activar-votacion
     */
    public function activarVotacion($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if ($this->propuestaModel->activarVotacion((int) $id)) {
                return $this->ok(null, 'Votación activada exitosamente');
            }

            return $this->fail('Error al activar votación', 500);
        });
    }

    /**
     * Eliminar propuesta (soft delete)
     * DELETE /api/propuestas/{id}
     */
    public function eliminar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->propuestaModel->find($id)) {
                return $this->fail('Propuesta no encontrada', 404);
            }

            if ($this->propuestaModel->delete($id)) {
                return $this->ok(null, 'Propuesta eliminada exitosamente');
            }

            return $this->fail('Error al eliminar propuesta', 500);
        });
    }

    /**
     * Obtener propuestas en votación
     * GET /api/propuestas/votacion
     */
    public function enVotacion()
    {
        return $this->attempt(function () {
            $perPage = $this->request->getVar('perPage') ?? 10;

            return $this->ok($this->propuestaModel->getEnVotacion((int) $perPage));
        });
    }

    /**
     * Obtener propuestas más votadas
     * GET /api/propuestas/populares/{limit}
     */
    public function populares($limit = 10)
    {
        return $this->attempt(function () use ($limit) {
            return $this->ok($this->propuestaModel->getMasVotadas((int) $limit));
        });
    }

    /**
     * Obtener propuestas por usuario
     * GET /api/propuestas/usuario/{userId}
     */
    public function porUsuario($userId = null)
    {
        return $this->attempt(function () use ($userId) {
            if (! $userId) {
                return $this->fail('ID de usuario requerido', 400);
            }

            return $this->ok($this->propuestaModel->getPropuestasPorUsuario((int) $userId));
        });
    }

    /**
     * Obtener estadísticas de propuestas
     * GET /api/propuestas/estadisticas
     */
    public function estadisticas()
    {
        return $this->attempt(function () {
            return $this->ok($this->propuestaModel->getEstadisticas());
        });
    }
}
