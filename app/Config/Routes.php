<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static function () {
    return service('response')->setJSON([
        'success' => true,
        'message' => 'Mapeo Vecinal API',
    ]);
});

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
