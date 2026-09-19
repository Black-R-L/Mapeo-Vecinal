<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

/**
 * CategoriasController
 *
 * Maneja CRUD de categorías
 * Requiere autenticación de admin
 *
 * @package App\Controllers
 */
class CategoriasController extends BaseApiController
{
    protected $categoriaModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Listar todas las categorías
     * GET /api/categorias
     */
    public function index()
    {
        return $this->attempt(function () {
            return $this->ok($this->categoriaModel->getAllCategorias());
        });
    }

    /**
     * Obtener categoría por ID
     * GET /api/categorias/{id}
     */
    public function obtener($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            $categoria = $this->categoriaModel->find($id);

            if (! $categoria) {
                return $this->fail('Categoría no encontrada', 404);
            }

            return $this->ok($categoria);
        });
    }

    /**
     * Crear nueva categoría
     * POST /api/categorias
     */
    public function crear()
    {
        return $this->attempt(function () {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (! $this->categoriaModel->validate($data)) {
                return $this->fail('Validación fallida', 422, $this->categoriaModel->errors());
            }

            if ($this->categoriaModel->insert($data)) {
                return $this->ok(null, 'Categoría creada exitosamente', 201);
            }

            return $this->fail('Error al crear categoría', 500);
        });
    }

    /**
     * Actualizar categoría
     * PUT /api/categorias/{id}
     */
    public function actualizar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->categoriaModel->find($id)) {
                return $this->fail('Categoría no encontrada', 404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->categoriaModel->update($id, $data)) {
                return $this->ok(null, 'Categoría actualizada exitosamente');
            }

            return $this->fail('Error al actualizar categoría', 500);
        });
    }

    /**
     * Eliminar categoría
     * DELETE /api/categorias/{id}
     */
    public function eliminar($id = null)
    {
        return $this->attempt(function () use ($id) {
            if (! $id) {
                return $this->fail('ID requerido', 400);
            }

            if (! $this->categoriaModel->find($id)) {
                return $this->fail('Categoría no encontrada', 404);
            }

            if ($this->categoriaModel->delete($id)) {
                return $this->ok(null, 'Categoría eliminada exitosamente');
            }

            return $this->fail('Error al eliminar categoría', 500);
        });
    }

    /**
     * Obtener categorías con contador de reportes
     * GET /api/categorias/estadisticas/conteo
     */
    public function conConteo()
    {
        return $this->attempt(function () {
            return $this->ok($this->categoriaModel->getCategoriasConConteo());
        });
    }
}
