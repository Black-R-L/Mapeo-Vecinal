<?php

namespace App\Controllers\Panel;

use App\Models\CategoriaModel;
use App\Models\PropuestaModel;
use App\Models\UsuarioModel;

/**
 * PropuestasPanelController
 *
 * CRUD completo de propuestas para el panel (autoridad/admin).
 *
 * @package App\Controllers\Panel
 */
class PropuestasPanelController extends BasePanelController
{
    private PropuestaModel $propuestaModel;
    private UsuarioModel $usuarioModel;
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->propuestaModel = new PropuestaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        return view('panel/propuestas/index', [
            'title' => 'Propuestas',
            'propuestas' => $this->propuestaModel
                ->select('propuestas.id, propuestas.titulo, propuestas.estado, propuestas.votos_totales, propuestas.created_at, usuarios.nombre as usuario, categorias.nombre as categoria, categorias.color, categorias.icono')
                ->join('usuarios', 'usuarios.id = propuestas.user_id')
                ->join('categorias', 'categorias.id = propuestas.categoria_id')
                ->orderBy('propuestas.id', 'DESC')
                ->findAll(200),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function editar(int $id)
    {
        $propuesta = $this->propuestaModel->find($id);

        if (! $propuesta) {
            return $this->conExito('/panel/propuestas', 'Esa propuesta no existe.');
        }

        return view('panel/propuestas/editar', [
            'title' => 'Editar propuesta',
            'item' => $propuesta,
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    private function datosFormulario(): array
    {
        return [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => (string) $this->request->getPost('estado'),
            'user_id' => (int) $this->request->getPost('user_id'),
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];
    }

    public function crear()
    {
        if (! $this->propuestaModel->insert($this->datosFormulario())) {
            return $this->conError('/panel/propuestas', $this->propuestaModel->errors());
        }

        return $this->conExito('/panel/propuestas', 'Propuesta creada correctamente.');
    }

    public function actualizar(int $id)
    {
        if (! $this->propuestaModel->find($id)) {
            return $this->conExito('/panel/propuestas', 'Esa propuesta no existe.');
        }

        if (! $this->propuestaModel->update($id, $this->datosFormulario())) {
            return $this->conError('/panel/propuestas/' . $id . '/editar', $this->propuestaModel->errors());
        }

        return $this->conExito('/panel/propuestas', 'Propuesta actualizada correctamente.');
    }

    public function eliminar(int $id)
    {
        if (! $this->propuestaModel->find($id)) {
            return $this->conExito('/panel/propuestas', 'Esa propuesta no existe.');
        }

        $this->propuestaModel->delete($id);

        return $this->conExito('/panel/propuestas', 'Propuesta eliminada correctamente.');
    }
}
