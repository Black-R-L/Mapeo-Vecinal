<?php

namespace App\Controllers;

use App\Models\VotacionModel;
use App\Models\PropuestaModel;
use CodeIgniter\Controller;

/**
 * VotacionesController
 * 
 * Maneja votaciones en propuestas
 * Los usuarios pueden votar una sola vez por propuesta
 * 
 * @package App\Controllers
 */
class VotacionesController extends Controller
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
     * GET /votaciones/propuesta/{propuestaId}
     */
    public function porPropuesta($propuestaId = null)
    {
        try {
            if (!$propuestaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de propuesta requerido',
                ])->setStatusCode(400);
            }

            $votantes = $this->votacionModel->getVotantesPorPropuesta($propuestaId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $votantes,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear nuevo voto
     * POST /votaciones
     */
    public function crear()
    {
        try {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!isset($data['user_id']) || !isset($data['propuesta_id']) || !isset($data['tipo_voto'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'user_id, propuesta_id y tipo_voto son requeridos',
                ])->setStatusCode(400);
            }

            // Verificar si usuario ya votó
            if ($this->votacionModel->yaVoto($data['user_id'], $data['propuesta_id'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El usuario ya votó en esta propuesta',
                ])->setStatusCode(409);
            }

            if (!$this->votacionModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validación fallida',
                    'errors' => $this->votacionModel->errors(),
                ])->setStatusCode(422);
            }

            if ($this->votacionModel->insert($data)) {
                // Incrementar contador de votos en propuesta
                $this->propuestaModel->incrementarVotos($data['propuesta_id']);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Voto registrado exitosamente',
                ])->setStatusCode(201);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al registrar voto',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Cambiar voto de usuario
     * PUT /votaciones/{userId}/{propuestaId}
     */
    public function cambiarVoto($userId = null, $propuestaId = null)
    {
        try {
            if (!$userId || !$propuestaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'user_id y propuesta_id requeridos',
                ])->setStatusCode(400);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!isset($data['tipo_voto'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'tipo_voto requerido',
                ])->setStatusCode(400);
            }

            if (!$this->votacionModel->yaVoto($userId, $propuestaId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El usuario no ha votado en esta propuesta',
                ])->setStatusCode(404);
            }

            if ($this->votacionModel->cambiarVoto($userId, $propuestaId, $data['tipo_voto'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Voto actualizado exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar voto',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar voto
     * DELETE /votaciones/{userId}/{propuestaId}
     */
    public function eliminar($userId = null, $propuestaId = null)
    {
        try {
            if (!$userId || !$propuestaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'user_id y propuesta_id requeridos',
                ])->setStatusCode(400);
            }

            if (!$this->votacionModel->yaVoto($userId, $propuestaId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El usuario no ha votado en esta propuesta',
                ])->setStatusCode(404);
            }

            if ($this->votacionModel->eliminarVoto($userId, $propuestaId)) {
                // Decrementar contador de votos en propuesta
                $this->propuestaModel->where('id', $propuestaId)->decrement('votos_totales');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Voto eliminado exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar voto',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener resumen de votación
     * GET /votaciones/resumen/{propuestaId}
     */
    public function resumen($propuestaId = null)
    {
        try {
            if (!$propuestaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de propuesta requerido',
                ])->setStatusCode(400);
            }

            $resumen = $this->votacionModel->getResumenVotacion($propuestaId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $resumen,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener votos del usuario
     * GET /votaciones/usuario/{userId}
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

            $votos = $this->votacionModel->getVotosDelUsuario($userId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $votos,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Verificar si usuario ya votó
     * GET /votaciones/verificar/{userId}/{propuestaId}
     */
    public function verificar($userId = null, $propuestaId = null)
    {
        try {
            if (!$userId || !$propuestaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'user_id y propuesta_id requeridos',
                ])->setStatusCode(400);
            }

            $yaVoto = $this->votacionModel->yaVoto($userId, $propuestaId);
            $voto = $this->votacionModel->getVoto($userId, $propuestaId);

            return $this->response->setJSON([
                'success' => true,
                'ya_voto' => $yaVoto,
                'voto' => $voto,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
