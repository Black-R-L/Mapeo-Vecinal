<?php

namespace App\Controllers\Panel;

use App\Models\CategoriaModel;
use App\Models\PropuestaModel;
use App\Models\ReporteModel;

/**
 * CategoriasPanelController
 *
 * CRUD completo de categorías para el panel (autoridad/admin).
 *
 * @package App\Controllers\Panel
 */
class CategoriasPanelController extends BasePanelController
{
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        return view('panel/categorias/index', [
            'title' => 'Categorías',
            'categorias' => $this->categoriaModel->orderBy('nombre', 'ASC')->findAll(100),
        ]);
    }

    public function editar(int $id)
    {
        $categoria = $this->categoriaModel->find($id);

        if (! $categoria) {
            return $this->conExito('/panel/categorias', 'Esa categoría no existe.');
        }

        return view('panel/categorias/editar', [
            'title' => 'Editar categoría',
            'item' => $categoria,
        ]);
    }

    public function crear()
    {
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'icono' => trim((string) $this->request->getPost('icono')),
            'color' => trim((string) $this->request->getPost('color')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
        ];

        if (! $this->categoriaModel->insert($data)) {
            return $this->conError('/panel/categorias', $this->categoriaModel->errors());
        }

        return $this->conExito('/panel/categorias', 'Categoría creada correctamente.');
    }

    public function actualizar(int $id)
    {
        if (! $this->categoriaModel->find($id)) {
            return $this->conExito('/panel/categorias', 'Esa categoría no existe.');
        }

        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'icono' => trim((string) $this->request->getPost('icono')),
            'color' => trim((string) $this->request->getPost('color')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
        ];

        // "nombre" es is_unique[categorias.nombre]; al editar debe ignorar el propio registro.
        $rules = $this->categoriaModel->getValidationRules();
        $rules['nombre'] = "required|string|min_length[3]|max_length[50]|is_unique[categorias.nombre,id,{$id}]";

        if (! $this->categoriaModel->setValidationRules($rules)->update($id, $data)) {
            return $this->conError('/panel/categorias/' . $id . '/editar', $this->categoriaModel->errors());
        }

        return $this->conExito('/panel/categorias', 'Categoría actualizada correctamente.');
    }

    public function eliminar(int $id)
    {
        if (! $this->categoriaModel->find($id)) {
            return $this->conExito('/panel/categorias', 'Esa categoría no existe.');
        }

        // La FK de reportes/propuestas hacia categorias es ON DELETE CASCADE:
        // si se permite borrar, se pierden todos sus reportes/propuestas. Se bloquea explícitamente.
        $reportesAsociados = (new ReporteModel())->where('categoria_id', $id)->countAllResults();
        $propuestasAsociadas = (new PropuestaModel())->where('categoria_id', $id)->countAllResults();

        if ($reportesAsociados > 0 || $propuestasAsociadas > 0) {
            return $this->conError('/panel/categorias', [
                'categoria' => "No se puede eliminar: tiene {$reportesAsociados} reporte(s) y {$propuestasAsociadas} propuesta(s) asociadas.",
            ]);
        }

        $this->categoriaModel->delete($id);

        return $this->conExito('/panel/categorias', 'Categoría eliminada correctamente.');
    }
}
