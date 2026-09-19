<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

/**
 * CategoriasController
 * 
 * Maneja CRUD de categorías
 * Requiere autenticación de admin
 * 
 * @package App\Controllers
 */
class CategoriasController extends Controller
{
    protected $categoriaModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Listar todas las categorías
     * GET /categorias
     */
    public function index()
    {
        try {
            $categorias = $this->categoriaModel->getAllCategorias();

            return $this->response->setJSON([
                'success' => true,
                'data' => $categorias,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener categoría por ID
     * GET /categorias/{id}
     */
    public function obtener($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            $categoria = $this->categoriaModel->find($id);

            if (!$categoria) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Categoría no encontrada',
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $categoria,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear nueva categoría
     * POST /categorias
     */
    public function crear()
    {
        try {
            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (!$this->categoriaModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validación fallida',
                    'errors' => $this->categoriaModel->errors(),
                ])->setStatusCode(422);
            }

            if ($this->categoriaModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría creada exitosamente',
                ])->setStatusCode(201);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear categoría',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar categoría
     * PUT /categorias/{id}
     */
    public function actualizar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            if (!$this->categoriaModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Categoría no encontrada',
                ])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if ($this->categoriaModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría actualizada exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar categoría',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar categoría
     * DELETE /categorias/{id}
     */
    public function eliminar($id = null)
    {
        try {
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID requerido',
                ])->setStatusCode(400);
            }

            if (!$this->categoriaModel->find($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Categoría no encontrada',
                ])->setStatusCode(404);
            }

            if ($this->categoriaModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría eliminada exitosamente',
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar categoría',
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener categorías con contador de reportes
     * GET /categorias/estadisticas/conteo
     */
    public function conConteo()
    {
        try {
            $categorias = $this->categoriaModel->getCategoriasConConteo();

            return $this->response->setJSON([
                'success' => true,
                'data' => $categorias,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
