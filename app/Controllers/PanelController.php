<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\PropuestaModel;
use App\Models\ReporteModel;
use App\Models\UsuarioModel;
use App\Models\VotacionModel;
use CodeIgniter\Controller;

class PanelController extends Controller
{
    private UsuarioModel $usuarioModel;
    private CategoriaModel $categoriaModel;
    private ReporteModel $reporteModel;
    private PropuestaModel $propuestaModel;
    private VotacionModel $votacionModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->categoriaModel = new CategoriaModel();
        $this->reporteModel = new ReporteModel();
        $this->propuestaModel = new PropuestaModel();
        $this->votacionModel = new VotacionModel();
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'usuarios' => $this->usuarioModel->countAllResults(),
                'categorias' => $this->categoriaModel->countAllResults(),
                'reportes' => $this->reporteModel->countAllResults(),
                'propuestas' => $this->propuestaModel->countAllResults(),
                'votaciones' => $this->votacionModel->countAllResults(),
            ],
            'reportesRecientes' => $this->reporteModel
                ->select('reportes.id, reportes.titulo, reportes.estado, reportes.created_at, categorias.nombre as categoria')
                ->join('categorias', 'categorias.id = reportes.categoria_id')
                ->orderBy('reportes.created_at', 'DESC')
                ->findAll(10),
            'propuestasRecientes' => $this->propuestaModel
                ->select('propuestas.id, propuestas.titulo, propuestas.estado, propuestas.votos_totales, propuestas.created_at')
                ->orderBy('propuestas.created_at', 'DESC')
                ->findAll(10),
        ];

        return view('panel/dashboard', $data);
    }

    public function usuarios()
    {
        return view('panel/usuarios', [
            'title' => 'Usuarios',
            'usuarios' => $this->usuarioModel
                ->select('id, nombre, email, rol, barrio, estado, created_at')
                ->orderBy('id', 'DESC')
                ->findAll(100),
        ]);
    }

    public function crearUsuario()
    {
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'rol' => (string) $this->request->getPost('rol'),
            'barrio' => trim((string) $this->request->getPost('barrio')),
            'estado' => (string) $this->request->getPost('estado'),
        ];

        if (! $this->usuarioModel->crearUsuario($data)) {
            return redirect()->to('/panel/usuarios')->withInput()->with('errors', $this->usuarioModel->errors());
        }

        return redirect()->to('/panel/usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function categorias()
    {
        return view('panel/categorias', [
            'title' => 'Categorias',
            'categorias' => $this->categoriaModel->orderBy('id', 'DESC')->findAll(100),
        ]);
    }

    public function crearCategoria()
    {
        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'icono' => trim((string) $this->request->getPost('icono')),
            'color' => trim((string) $this->request->getPost('color')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
        ];

        if (! $this->categoriaModel->insert($data)) {
            return redirect()->to('/panel/categorias')->withInput()->with('errors', $this->categoriaModel->errors());
        }

        return redirect()->to('/panel/categorias')->with('success', 'Categoria creada correctamente.');
    }

    public function reportes()
    {
        return view('panel/reportes', [
            'title' => 'Reportes',
            'reportes' => $this->reporteModel
                ->select('reportes.id, reportes.titulo, reportes.estado, reportes.votos_totales, reportes.created_at, usuarios.nombre as usuario, categorias.nombre as categoria')
                ->join('usuarios', 'usuarios.id = reportes.user_id')
                ->join('categorias', 'categorias.id = reportes.categoria_id')
                ->orderBy('reportes.id', 'DESC')
                ->findAll(100),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function crearReporte()
    {
        $latitud = trim((string) $this->request->getPost('latitud'));
        $longitud = trim((string) $this->request->getPost('longitud'));

        $data = [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => (string) $this->request->getPost('estado'),
            'latitud' => $latitud === '' ? null : $latitud,
            'longitud' => $longitud === '' ? null : $longitud,
            'foto' => null,
            'user_id' => (int) $this->request->getPost('user_id'),
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];

        if (! $this->reporteModel->insert($data)) {
            return redirect()->to('/panel/reportes')->withInput()->with('errors', $this->reporteModel->errors());
        }

        return redirect()->to('/panel/reportes')->with('success', 'Reporte creado correctamente.');
    }

    public function propuestas()
    {
        return view('panel/propuestas', [
            'title' => 'Propuestas',
            'propuestas' => $this->propuestaModel
                ->select('propuestas.id, propuestas.titulo, propuestas.estado, propuestas.votos_totales, propuestas.created_at, usuarios.nombre as usuario, categorias.nombre as categoria')
                ->join('usuarios', 'usuarios.id = propuestas.user_id')
                ->join('categorias', 'categorias.id = propuestas.categoria_id')
                ->orderBy('propuestas.id', 'DESC')
                ->findAll(100),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->select('id, nombre')->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function crearPropuesta()
    {
        $data = [
            'titulo' => trim((string) $this->request->getPost('titulo')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'estado' => (string) $this->request->getPost('estado'),
            'user_id' => (int) $this->request->getPost('user_id'),
            'categoria_id' => (int) $this->request->getPost('categoria_id'),
        ];

        if (! $this->propuestaModel->insert($data)) {
            return redirect()->to('/panel/propuestas')->withInput()->with('errors', $this->propuestaModel->errors());
        }

        return redirect()->to('/panel/propuestas')->with('success', 'Propuesta creada correctamente.');
    }

    public function votaciones()
    {
        return view('panel/votaciones', [
            'title' => 'Votaciones',
            'votaciones' => $this->votacionModel
                ->select('votaciones.id, votaciones.tipo_voto, votaciones.fecha, usuarios.nombre as usuario, propuestas.titulo as propuesta')
                ->join('usuarios', 'usuarios.id = votaciones.user_id')
                ->join('propuestas', 'propuestas.id = votaciones.propuesta_id')
                ->orderBy('votaciones.id', 'DESC')
                ->findAll(100),
            'usuarios' => $this->usuarioModel->select('id, nombre')->where('estado', 'activo')->orderBy('nombre', 'ASC')->findAll(),
            'propuestas' => $this->propuestaModel->select('id, titulo')->orderBy('titulo', 'ASC')->findAll(),
        ]);
    }

    public function crearVotacion()
    {
        $userId = (int) $this->request->getPost('user_id');
        $propuestaId = (int) $this->request->getPost('propuesta_id');
        $tipoVoto = (string) $this->request->getPost('tipo_voto');

        if ($this->votacionModel->yaVoto($userId, $propuestaId)) {
            return redirect()->to('/panel/votaciones')->withInput()->with('errors', ['duplicado' => 'Ese usuario ya voto en esta propuesta.']);
        }

        $data = [
            'user_id' => $userId,
            'propuesta_id' => $propuestaId,
            'tipo_voto' => $tipoVoto,
        ];

        if (! $this->votacionModel->insert($data)) {
            return redirect()->to('/panel/votaciones')->withInput()->with('errors', $this->votacionModel->errors());
        }

        $this->propuestaModel->incrementarVotos($propuestaId);

        return redirect()->to('/panel/votaciones')->with('success', 'Votacion creada correctamente.');
    }
}

