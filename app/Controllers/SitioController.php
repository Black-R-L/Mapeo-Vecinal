<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\PropuestaModel;
use App\Models\ReporteModel;
use App\Models\VotacionModel;
use CodeIgniter\Controller;

/**
 * SitioController
 *
 * Fachada pública: el mapa de reportes es la puerta de entrada, más los
 * listados y formularios de reportes/propuestas para los vecinos.
 *
 * @package App\Controllers
 */
class SitioController extends Controller
{
    private ReporteModel $reporteModel;
    private PropuestaModel $propuestaModel;
    private CategoriaModel $categoriaModel;
    private VotacionModel $votacionModel;

    public function __construct()
    {
        $this->reporteModel = new ReporteModel();
        $this->propuestaModel = new PropuestaModel();
        $this->categoriaModel = new CategoriaModel();
        $this->votacionModel = new VotacionModel();
    }

    public function index()
    {
        return view('sitio/home', [
            'title' => 'Mapeo Vecinal',
            'categorias' => $this->categoriaModel->getAllCategorias(),
            'barrios' => $this->reporteModel->getBarriosConReportes(),
            'statsReportes' => $this->reporteModel->getEstadisticas(),
            'propuestasDestacadas' => $this->propuestaModel->getMasVotadas(4),
        ]);
    }

    /**
     * Datos del mapa en JSON, consumidos por public/assets/js/mapa.js.
     * GET /mapa/datos?categoria_id=&estado=&barrio=
     */
    public function mapaDatos()
    {
        $builder = $this->reporteModel
            ->select('reportes.id, reportes.titulo, reportes.estado, reportes.votos_totales, reportes.latitud, reportes.longitud, usuarios.barrio, categorias.nombre as categoria, categorias.color, categorias.icono')
            ->join('usuarios', 'usuarios.id = reportes.user_id')
            ->join('categorias', 'categorias.id = reportes.categoria_id')
            ->where('reportes.latitud IS NOT NULL')
            ->where('reportes.longitud IS NOT NULL');

        $categoriaIds = $this->request->getGet('categoria_id');
        $estado = $this->request->getGet('estado');
        $barrio = $this->request->getGet('barrio');

        if ($categoriaIds) {
            $builder->whereIn('reportes.categoria_id', array_map('intval', explode(',', (string) $categoriaIds)));
        }

        if ($estado) {
            $builder->where('reportes.estado', $estado);
        }

        if ($barrio) {
            $builder->where('usuarios.barrio', $barrio);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $builder->orderBy('reportes.created_at', 'DESC')->findAll(),
        ]);
    }

    public function reportes()
    {
        $filtros = [
            'estado' => (string) $this->request->getGet('estado'),
            'categoria_id' => (string) $this->request->getGet('categoria_id'),
            'barrio' => (string) $this->request->getGet('barrio'),
        ];

        return view('sitio/reportes_index', [
            'title' => 'Reportes del barrio',
            'reportes' => $this->reporteModel->getFiltrados($filtros, 12),
            'categorias' => $this->categoriaModel->getAllCategorias(),
            'barrios' => $this->reporteModel->getBarriosConReportes(),
            'filtros' => $filtros,
        ]);
    }

    public function reporte(int $id)
    {
        $reporte = $this->reporteModel
            ->select('reportes.*, usuarios.nombre as autor, usuarios.barrio, categorias.nombre as categoria, categorias.color, categorias.icono')
            ->join('usuarios', 'usuarios.id = reportes.user_id')
            ->join('categorias', 'categorias.id = reportes.categoria_id')
            ->where('reportes.id', $id)
            ->first();

        if (! $reporte) {
            return redirect()->to('/reportes')->with('errors', ['reporte' => 'Ese reporte no existe.']);
        }

        return view('sitio/reporte_detalle', ['title' => $reporte['titulo'], 'reporte' => $reporte]);
    }

    public function nuevoReporte()
    {
        return view('sitio/reporte_form', [
            'title' => 'Nuevo reporte',
            'categorias' => $this->categoriaModel->getAllCategorias(),
        ]);
    }

    public function crearReporte()
    {
        $usuario = session()->get('usuario');
        $latitud = trim((string) $this->request->getPost('latitud'));
        $longitud = trim((string) $this->request->getPost('longitud'));

        $data = [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => 'nuevo',
            'latitud' => $latitud === '' ? null : $latitud,
            'longitud' => $longitud === '' ? null : $longitud,
            'user_id' => $usuario['id'],
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];

        if (! $this->reporteModel->insert($data)) {
            return redirect()->to('/reportes/nuevo')->withInput()->with('errors', $this->reporteModel->errors());
        }

        return redirect()->to('/reportes/' . $this->reporteModel->getInsertID())
            ->with('success', '¡Gracias! Tu reporte fue publicado en el mapa.');
    }

    public function propuestas()
    {
        $estado = (string) $this->request->getGet('estado');

        return view('sitio/propuestas_index', [
            'title' => 'Propuestas vecinales',
            'propuestas' => $this->propuestaModel->getPropuestasConDetalles(12, (int) ($this->request->getGet('page') ?? 1), $estado),
            'estado' => $estado,
        ]);
    }

    public function propuesta(int $id)
    {
        $propuesta = $this->propuestaModel
            ->select('propuestas.*, usuarios.nombre as autor, categorias.nombre as categoria, categorias.color, categorias.icono')
            ->join('usuarios', 'usuarios.id = propuestas.user_id')
            ->join('categorias', 'categorias.id = propuestas.categoria_id')
            ->where('propuestas.id', $id)
            ->first();

        if (! $propuesta) {
            return redirect()->to('/propuestas')->with('errors', ['propuesta' => 'Esa propuesta no existe.']);
        }

        $usuario = session()->get('usuario');

        return view('sitio/propuesta_detalle', [
            'title' => $propuesta['titulo'],
            'propuesta' => $propuesta,
            'resumen' => $this->votacionModel->getResumenVotacion($id),
            'miVoto' => $usuario ? $this->votacionModel->getVoto($usuario['id'], $id) : null,
        ]);
    }

    public function nuevaPropuesta()
    {
        return view('sitio/propuesta_form', [
            'title' => 'Nueva propuesta',
            'categorias' => $this->categoriaModel->getAllCategorias(),
        ]);
    }

    public function crearPropuesta()
    {
        $usuario = session()->get('usuario');

        $data = [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => 'en_votacion',
            'user_id' => $usuario['id'],
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];

        if (! $this->propuestaModel->insert($data)) {
            return redirect()->to('/propuestas/nueva')->withInput()->with('errors', $this->propuestaModel->errors());
        }

        return redirect()->to('/propuestas/' . $this->propuestaModel->getInsertID())
            ->with('success', '¡Propuesta publicada! Ya puede recibir votos.');
    }

    public function votar(int $id)
    {
        $usuario = session()->get('usuario');
        $tipoVoto = (string) $this->request->getPost('tipo_voto');

        if (! $this->propuestaModel->find($id)) {
            return redirect()->to('/propuestas')->with('errors', ['propuesta' => 'Esa propuesta no existe.']);
        }

        if ($this->votacionModel->yaVoto($usuario['id'], $id)) {
            return redirect()->to('/propuestas/' . $id)->with('errors', ['voto' => 'Ya votaste en esta propuesta.']);
        }

        if (! in_array($tipoVoto, ['favor', 'contra'], true)) {
            return redirect()->to('/propuestas/' . $id)->with('errors', ['voto' => 'Voto inválido.']);
        }

        $this->votacionModel->insert([
            'user_id' => $usuario['id'],
            'propuesta_id' => $id,
            'tipo_voto' => $tipoVoto,
        ]);

        if ($tipoVoto === 'favor') {
            $this->propuestaModel->incrementarVotos($id);
        }

        return redirect()->to('/propuestas/' . $id)->with('success', '¡Tu voto fue registrado!');
    }
}
