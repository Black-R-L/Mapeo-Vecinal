<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ============================================================
// API REST (JSON) - sin cambios de comportamiento, solo agrupada
// ============================================================
$routes->group('api', function ($routes) {
    // Usuarios
    $routes->get('usuarios', 'UsuariosController::index');
    $routes->get('usuarios/(:num)', 'UsuariosController::obtener/$1');
    $routes->post('usuarios', 'UsuariosController::crear');
    $routes->put('usuarios/(:num)', 'UsuariosController::actualizar/$1');
    $routes->delete('usuarios/(:num)', 'UsuariosController::eliminar/$1');
    $routes->post('usuarios/(:num)/cambiar-password', 'UsuariosController::cambiarPassword/$1');
    $routes->get('usuarios/barrio/(:any)', 'UsuariosController::porBarrio/$1');
    $routes->get('usuarios/estadisticas', 'UsuariosController::estadisticas');

    // Categorias
    $routes->get('categorias', 'CategoriasController::index');
    $routes->get('categorias/(:num)', 'CategoriasController::obtener/$1');
    $routes->post('categorias', 'CategoriasController::crear');
    $routes->put('categorias/(:num)', 'CategoriasController::actualizar/$1');
    $routes->delete('categorias/(:num)', 'CategoriasController::eliminar/$1');
    $routes->get('categorias/estadisticas/conteo', 'CategoriasController::conConteo');

    // Reportes
    $routes->get('reportes', 'ReportesController::index');
    $routes->get('reportes/(:num)', 'ReportesController::obtener/$1');
    $routes->post('reportes', 'ReportesController::crear');
    $routes->put('reportes/(:num)', 'ReportesController::actualizar/$1');
    $routes->delete('reportes/(:num)', 'ReportesController::eliminar/$1');
    $routes->patch('reportes/(:num)/estado', 'ReportesController::cambiarEstado/$1');
    $routes->get('reportes/categoria/(:num)', 'ReportesController::porCategoria/$1');
    $routes->get('reportes/usuario/(:num)', 'ReportesController::porUsuario/$1');
    $routes->post('reportes/filtro', 'ReportesController::filtro');
    $routes->get('reportes/populares/(:num)', 'ReportesController::populares/$1');
    $routes->get('reportes/estadisticas', 'ReportesController::estadisticas');

    // Propuestas
    $routes->get('propuestas', 'PropuestasController::index');
    $routes->get('propuestas/(:num)', 'PropuestasController::obtener/$1');
    $routes->post('propuestas', 'PropuestasController::crear');
    $routes->put('propuestas/(:num)', 'PropuestasController::actualizar/$1');
    $routes->delete('propuestas/(:num)', 'PropuestasController::eliminar/$1');
    $routes->patch('propuestas/(:num)/estado', 'PropuestasController::cambiarEstado/$1');
    $routes->patch('propuestas/(:num)/activar-votacion', 'PropuestasController::activarVotacion/$1');
    $routes->get('propuestas/votacion', 'PropuestasController::enVotacion');
    $routes->get('propuestas/populares/(:num)', 'PropuestasController::populares/$1');
    $routes->get('propuestas/usuario/(:num)', 'PropuestasController::porUsuario/$1');
    $routes->get('propuestas/estadisticas', 'PropuestasController::estadisticas');

    // Votaciones
    $routes->post('votaciones', 'VotacionesController::crear');
    $routes->put('votaciones/(:num)/(:num)', 'VotacionesController::cambiarVoto/$1/$2');
    $routes->delete('votaciones/(:num)/(:num)', 'VotacionesController::eliminar/$1/$2');
    $routes->get('votaciones/propuesta/(:num)', 'VotacionesController::porPropuesta/$1');
    $routes->get('votaciones/resumen/(:num)', 'VotacionesController::resumen/$1');
    $routes->get('votaciones/usuario/(:num)', 'VotacionesController::porUsuario/$1');
    $routes->get('votaciones/verificar/(:num)/(:num)', 'VotacionesController::verificar/$1/$2');
});

// ============================================================
// Autenticación
// ============================================================
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::procesarLogin', ['filter' => 'csrf']);
$routes->get('registro', 'AuthController::registro');
$routes->post('registro', 'AuthController::procesarRegistro', ['filter' => 'csrf']);
$routes->get('logout', 'AuthController::logout');

// ============================================================
// Sitio público - el mapa es la puerta de entrada
// ============================================================
$routes->get('/', 'SitioController::index');
$routes->get('mapa/datos', 'SitioController::mapaDatos');

$routes->get('reportes', 'SitioController::reportes');
$routes->get('reportes/nuevo', 'SitioController::nuevoReporte', ['filter' => 'auth']);
$routes->post('reportes/nuevo', 'SitioController::crearReporte', ['filter' => ['auth', 'csrf']]);
$routes->get('reportes/(:num)', 'SitioController::reporte/$1');

$routes->get('propuestas', 'SitioController::propuestas');
$routes->get('propuestas/nueva', 'SitioController::nuevaPropuesta', ['filter' => 'auth']);
$routes->post('propuestas/nueva', 'SitioController::crearPropuesta', ['filter' => ['auth', 'csrf']]);
$routes->post('propuestas/(:num)/votar', 'SitioController::votar/$1', ['filter' => ['auth', 'csrf']]);
$routes->get('propuestas/(:num)', 'SitioController::propuesta/$1');

// ============================================================
// Panel administrativo (autoridad/admin)
// ============================================================
$routes->group('panel', ['filter' => 'auth:admin,autoridad'], static function ($routes) {
    $routes->get('/', 'Panel\DashboardPanelController::index');

    // Usuarios: solo admin
    $routes->group('usuarios', ['filter' => 'auth:admin'], static function ($routes) {
        $routes->get('/', 'Panel\UsuariosPanelController::index');
        $routes->post('/', 'Panel\UsuariosPanelController::crear', ['filter' => 'csrf']);
        $routes->get('(:num)/editar', 'Panel\UsuariosPanelController::editar/$1');
        $routes->post('(:num)/editar', 'Panel\UsuariosPanelController::actualizar/$1', ['filter' => 'csrf']);
        $routes->post('(:num)/eliminar', 'Panel\UsuariosPanelController::eliminar/$1', ['filter' => 'csrf']);
    });

    $routes->group('categorias', static function ($routes) {
        $routes->get('/', 'Panel\CategoriasPanelController::index');
        $routes->post('/', 'Panel\CategoriasPanelController::crear', ['filter' => 'csrf']);
        $routes->get('(:num)/editar', 'Panel\CategoriasPanelController::editar/$1');
        $routes->post('(:num)/editar', 'Panel\CategoriasPanelController::actualizar/$1', ['filter' => 'csrf']);
        $routes->post('(:num)/eliminar', 'Panel\CategoriasPanelController::eliminar/$1', ['filter' => 'csrf']);
    });

    $routes->group('reportes', static function ($routes) {
        $routes->get('/', 'Panel\ReportesPanelController::index');
        $routes->post('/', 'Panel\ReportesPanelController::crear', ['filter' => 'csrf']);
        $routes->get('(:num)/editar', 'Panel\ReportesPanelController::editar/$1');
        $routes->post('(:num)/editar', 'Panel\ReportesPanelController::actualizar/$1', ['filter' => 'csrf']);
        $routes->post('(:num)/eliminar', 'Panel\ReportesPanelController::eliminar/$1', ['filter' => 'csrf']);
    });

    $routes->group('propuestas', static function ($routes) {
        $routes->get('/', 'Panel\PropuestasPanelController::index');
        $routes->post('/', 'Panel\PropuestasPanelController::crear', ['filter' => 'csrf']);
        $routes->get('(:num)/editar', 'Panel\PropuestasPanelController::editar/$1');
        $routes->post('(:num)/editar', 'Panel\PropuestasPanelController::actualizar/$1', ['filter' => 'csrf']);
        $routes->post('(:num)/eliminar', 'Panel\PropuestasPanelController::eliminar/$1', ['filter' => 'csrf']);
    });

    $routes->group('votaciones', static function ($routes) {
        $routes->get('/', 'Panel\VotacionesPanelController::index');
        $routes->post('/', 'Panel\VotacionesPanelController::crear', ['filter' => 'csrf']);
        $routes->post('(:num)/eliminar', 'Panel\VotacionesPanelController::eliminar/$1', ['filter' => 'csrf']);
    });
});

