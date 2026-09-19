<?php

namespace App\Controllers\Panel;

use App\Models\CategoriaModel;
use App\Models\ReporteModel;
use App\Models\UsuarioModel;

/**
 * ReportesPanelController
 *
 * CRUD completo de reportes para el panel (autoridad/admin).
 *
 * @package App\Controllers\Panel
 */
class ReportesPanelController extends BasePanelController
{
    private ReporteModel $reporteModel;
    private UsuarioModel $usuarioModel;
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->reporteModel = new ReporteModel();
        $this->usuarioModel = new UsuarioModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        return view('panel/reportes/index', [
            'title' => 'Reportes',
            'reportes' => $this->reporteModel
                ->select('reportes.id, reportes.titulo, reportes.estado, reportes.votos_totales, reportes.created_at, usuarios.nombre as usuario, categorias.nombre as categoria, categorias.color, categorias.icono')
                ->join('usuarios', 'usuarios.id = reportes.user_id')
                ->join('categorias', 'categorias.id = reportes.categoria_id')
                ->orderBy('reportes.id', 'DESC')
                ->findAll(200),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function editar(int $id)
    {
        $reporte = $this->reporteModel->find($id);

        if (! $reporte) {
            return $this->conExito('/panel/reportes', 'Ese reporte no existe.');
        }

        return view('panel/reportes/editar', [
            'title' => 'Editar reporte',
            'item' => $reporte,
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    private function datosFormulario(): array
    {
        $latitud = trim((string) $this->request->getPost('latitud'));
        $longitud = trim((string) $this->request->getPost('longitud'));

        return [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => (string) $this->request->getPost('estado'),
            'latitud' => $latitud === '' ? null : $latitud,
            'longitud' => $longitud === '' ? null : $longitud,
            'user_id' => (int) $this->request->getPost('user_id'),
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];
    }

    public function crear()
    {
        $data = $this->datosFormulario();
        $data['foto'] = null;

        if (! $this->reporteModel->insert($data)) {
            return $this->conError('/panel/reportes', $this->reporteModel->errors());
        }

        return $this->conExito('/panel/reportes', 'Reporte creado correctamente.');
    }

    public function actualizar(int $id)
    {
        if (! $this->reporteModel->find($id)) {
            return $this->conExito('/panel/reportes', 'Ese reporte no existe.');
        }

        if (! $this->reporteModel->update($id, $this->datosFormulario())) {
            return $this->conError('/panel/reportes/' . $id . '/editar', $this->reporteModel->errors());
        }

        return $this->conExito('/panel/reportes', 'Reporte actualizado correctamente.');
    }

    public function eliminar(int $id)
    {
        if (! $this->reporteModel->find($id)) {
            return $this->conExito('/panel/reportes', 'Ese reporte no existe.');
        }

        $this->reporteModel->delete($id);

        return $this->conExito('/panel/reportes', 'Reporte eliminado correctamente.');
    }
}
