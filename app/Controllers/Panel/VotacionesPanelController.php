<?php

namespace App\Controllers\Panel;

use App\Models\PropuestaModel;
use App\Models\UsuarioModel;
use App\Models\VotacionModel;

/**
 * VotacionesPanelController
 *
 * Gestión de votaciones para el panel. No tiene "editar": una votación
 * se crea o se elimina, no se reinterpreta.
 *
 * @package App\Controllers\Panel
 */
class VotacionesPanelController extends BasePanelController
{
    private VotacionModel $votacionModel;
    private PropuestaModel $propuestaModel;
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->votacionModel = new VotacionModel();
        $this->propuestaModel = new PropuestaModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        return view('panel/votaciones/index', [
            'title' => 'Votaciones',
            'votaciones' => $this->votacionModel
                ->select('votaciones.id, votaciones.tipo_voto, votaciones.fecha, usuarios.nombre as usuario, propuestas.titulo as propuesta')
                ->join('usuarios', 'usuarios.id = votaciones.user_id')
                ->join('propuestas', 'propuestas.id = votaciones.propuesta_id')
                ->orderBy('votaciones.id', 'DESC')
                ->findAll(200),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'propuestas' => $this->propuestaModel->select('id, titulo')->orderBy('titulo', 'ASC')->findAll(),
        ]);
    }

    public function crear()
    {
        $userId = (int) $this->request->getPost('user_id');
        $propuestaId = (int) $this->request->getPost('propuesta_id');
        $tipoVoto = (string) $this->request->getPost('tipo_voto');

        if ($this->votacionModel->yaVoto($userId, $propuestaId)) {
            return $this->conError('/panel/votaciones', ['duplicado' => 'Ese usuario ya votó en esta propuesta.']);
        }

        if (! $this->votacionModel->insert(['user_id' => $userId, 'propuesta_id' => $propuestaId, 'tipo_voto' => $tipoVoto])) {
            return $this->conError('/panel/votaciones', $this->votacionModel->errors());
        }

        // Solo los votos "a favor" mueven el contador de la propuesta.
        if ($tipoVoto === 'favor') {
            $this->propuestaModel->incrementarVotos($propuestaId);
        }

        return $this->conExito('/panel/votaciones', 'Votación creada correctamente.');
    }

    public function eliminar(int $id)
    {
        $voto = $this->votacionModel->find($id);

        if (! $voto) {
            return $this->conExito('/panel/votaciones', 'Esa votación no existe.');
        }

        $this->votacionModel->delete($id);

        if ($voto['tipo_voto'] === 'favor') {
            $this->propuestaModel->decrementarVotos($voto['propuesta_id']);
        }

        return $this->conExito('/panel/votaciones', 'Votación eliminada correctamente.');
    }
}
