<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CategoriaModel
 * 
 * Gestiona categorías de reportes y propuestas
 * Incluye información de icono y color para UI
 * 
 * @package App\Models
 */
class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nombre', 'icono', 'color', 'descripcion'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    // La tabla no tiene columna updated_at: dejarlo en el valor por defecto
    // rompía insert()/update() con "Unknown column 'updated_at' in field list".
    protected $updatedField     = '';

    protected $validationRules  = [
        'nombre'      => 'required|string|min_length[3]|max_length[50]|is_unique[categorias.nombre]',
        'icono'       => 'string|max_length[50]',
        'color'       => 'string|regex_match[/#[0-9A-Fa-f]{6}/]',
        'descripcion' => 'string|max_length[500]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'is_unique' => 'Esta categoría ya existe',
        ],
        'color' => [
            'regex_match' => 'El color debe ser un código hexadecimal válido (#RRGGBB)',
        ],
    ];

    /**
     * Obtener todas las categorías
     * 
     * @return array
     */
    public function getAllCategorias()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener categoría por nombre
     * 
     * @param string $nombre
     * @return array|null
     */
    public function getByNombre(string $nombre)
    {
        return $this->where('nombre', $nombre)->first();
    }

    /**
     * Contar total de categorías
     * 
     * @return int
     */
    public function contarTotal()
    {
        return $this->countAllResults();
    }

    /**
     * Obtener categorías con contador de reportes
     * 
     * @return array
     */
    public function getCategoriasConConteo()
    {
        return $this->select('categorias.*, COUNT(reportes.id) as total_reportes')
                    ->join('reportes', 'reportes.categoria_id = categorias.id', 'left')
                    ->groupBy('categorias.id')
                    ->orderBy('total_reportes', 'DESC')
                    ->findAll();
    }
}
