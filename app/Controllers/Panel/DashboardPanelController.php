<?php

namespace App\Controllers\Panel;

use App\Models\CategoriaModel;
use App\Models\PropuestaModel;
use App\Models\ReporteModel;
use App\Models\UsuarioModel;
use App\Models\VotacionModel;

/**
 * DashboardPanelController
 *
 * Resumen general para autoridad/admin: conteos y actividad reciente.
 *
 * @package App\Controllers\Panel
 */
class DashboardPanelController extends BasePanelController
{
    public function index()
    {
        $usuarioModel = new UsuarioModel();
        $categoriaModel = new CategoriaModel();
        $reporteModel = new ReporteModel();
        $propuestaModel = new PropuestaModel();
        $votacionModel = new VotacionModel();

        return view('panel/dashboard', [
            'title' => 'Panel de administración',
            'stats' => [
                'usuarios' => $usuarioModel->countAllResults(),
                'categorias' => $categoriaModel->countAllResults(),
                'reportes' => $reporteModel->countAllResults(),
                'propuestas' => $propuestaModel->countAllResults(),
                'votaciones' => $votacionModel->countAllResults(),
            ],
            'reportesRecientes' => $reporteModel
                ->select('reportes.id, reportes.titulo, reportes.estado, reportes.created_at, categorias.nombre as categoria, categorias.color, categorias.icono')
                ->join('categorias', 'categorias.id = reportes.categoria_id')
                ->orderBy('reportes.created_at', 'DESC')
                ->findAll(8),
            'propuestasRecientes' => $propuestaModel
                ->select('propuestas.id, propuestas.titulo, propuestas.estado, propuestas.votos_totales, propuestas.created_at')
                ->orderBy('propuestas.created_at', 'DESC')
                ->findAll(8),
        ]);
    }
}
