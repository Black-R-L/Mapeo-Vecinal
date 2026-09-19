<?php

namespace App\Controllers;

use App\Models\VotacionModel;
use App\Models\PropuestaModel;

/**
 * VotacionesController
 *
 * Maneja votaciones en propuestas
 * Los usuarios pueden votar una sola vez por propuesta
 *
 * @package App\Controllers
 */
class VotacionesController extends BaseApiController
{
    protected $votacionModel;
    protected $propuestaModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->votacionModel = new VotacionModel();
        $this->propuestaModel = new PropuestaModel();
    }

    /**
     * Obtener votos de una propuesta
     * GET /api/votaciones/propuesta/{propuestaId}
     */
    public function porPropuesta($propuestaId = null)
    {
        return $this->attempt(function () use ($propuestaId) {
            if (! $propuestaId) {
                return $this->fail('ID de propuesta requerido', 400);
            }

            return $this->ok($this->votacionModel->getVotantesPorPropuesta((int) $propuestaId));
        });
    }

    /**
     * Crear nuevo voto
     * POST /api/votaciones
     */
    public function crear()
    {
        return $this->attempt(function () {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! isset($data['user_id'], $data['propuesta_id'], $data['tipo_voto'])) {
                return $this->fail('user_id, propuesta_id y tipo_voto son requeridos', 400);
            }

            if ($this->votacionModel->yaVoto($data['user_id'], $data['propuesta_id'])) {
                return $this->fail('El usuario ya votó en esta propuesta', 409);
            }

            if (! $this->votacionModel->validate($data)) {
                return $this->fail('Validación fallida', 422, $this->votacionModel->errors());
            }

            if ($this->votacionModel->insert($data)) {
                if ($data['tipo_voto'] === 'favor') {
                    $this->propuestaModel->incrementarVotos((int) $data['propuesta_id']);
                }

                return $this->ok(null, 'Voto registrado exitosamente', 201);
            }

            return $this->fail('Error al registrar voto', 500);
        });
    }

    /**
     * Cambiar voto de usuario
     * PUT /api/votaciones/{userId}/{propuestaId}
     */
    public function cambiarVoto($userId = null, $propuestaId = null)
    {
        return $this->attempt(function () use ($userId, $propuestaId) {
            if (! $userId || ! $propuestaId) {
                return $this->fail('user_id y propuesta_id requeridos', 400);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! isset($data['tipo_voto'])) {
                return $this->fail('tipo_voto requerido', 400);
            }

            $votoActual = $this->votacionModel->getVoto((int) $userId, (int) $propuestaId);

            if (! $votoActual) {
                return $this->fail('El usuario no ha votado en esta propuesta', 404);
            }

            if ($this->votacionModel->cambiarVoto((int) $userId, (int) $propuestaId, $data['tipo_voto'])) {
                // Ajustar el contador si el sentido del voto cambió
                if ($votoActual['tipo_voto'] !== $data['tipo_voto']) {
                    if ($data['tipo_voto'] === 'favor') {
                        $this->propuestaModel->incrementarVotos((int) $propuestaId);
                    } else {
                        $this->propuestaModel->decrementarVotos((int) $propuestaId);
                    }
                }

                return $this->ok(null, 'Voto actualizado exitosamente');
            }

            return $this->fail('Error al actualizar voto', 500);
        });
    }

    /**
     * Eliminar voto
     * DELETE /api/votaciones/{userId}/{propuestaId}
     */
    public function eliminar($userId = null, $propuestaId = null)
    {
        return $this->attempt(function () use ($userId, $propuestaId) {
            if (! $userId || ! $propuestaId) {
                return $this->fail('user_id y propuesta_id requeridos', 400);
            }

            $voto = $this->votacionModel->getVoto((int) $userId, (int) $propuestaId);

            if (! $voto) {
                return $this->fail('El usuario no ha votado en esta propuesta', 404);
            }

            if ($this->votacionModel->eliminarVoto((int) $userId, (int) $propuestaId)) {
                if ($voto['tipo_voto'] === 'favor') {
                    $this->propuestaModel->decrementarVotos((int) $propuestaId);
                }

                return $this->ok(null, 'Voto eliminado exitosamente');
            }

            return $this->fail('Error al eliminar voto', 500);
        });
    }

    /**
     * Obtener resumen de votación
     * GET /api/votaciones/resumen/{propuestaId}
     */
    public function resumen($propuestaId = null)
    {
        return $this->attempt(function () use ($propuestaId) {
            if (! $propuestaId) {
                return $this->fail('ID de propuesta requerido', 400);
            }

            return $this->ok($this->votacionModel->getResumenVotacion((int) $propuestaId));
        });
    }

    /**
     * Obtener votos del usuario
     * GET /api/votaciones/usuario/{userId}
     */
    public function porUsuario($userId = null)
    {
        return $this->attempt(function () use ($userId) {
            if (! $userId) {
                return $this->fail('ID de usuario requerido', 400);
            }

            return $this->ok($this->votacionModel->getVotosDelUsuario((int) $userId));
        });
    }

    /**
     * Verificar si usuario ya votó
     * GET /api/votaciones/verificar/{userId}/{propuestaId}
     */
    public function verificar($userId = null, $propuestaId = null)
    {
        return $this->attempt(function () use ($userId, $propuestaId) {
            if (! $userId || ! $propuestaId) {
                return $this->fail('user_id y propuesta_id requeridos', 400);
            }

            return $this->ok([
                'ya_voto' => $this->votacionModel->yaVoto((int) $userId, (int) $propuestaId),
                'voto' => $this->votacionModel->getVoto((int) $userId, (int) $propuestaId),
            ]);
        });
    }
}
