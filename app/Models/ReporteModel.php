<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ReporteModel
 * 
 * Gestiona reportes de problemas reportados por ciudadanos
 * Incluye ubicación GPS, fotos y estado
 * 
 * @package App\Models
 */
class ReporteModel extends Model
{
    protected $table            = 'reportes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['titulo', 'descripcion', 'estado', 'latitud', 'longitud', 'foto', 'user_id', 'categoria_id', 'votos_totales'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'titulo'      => 'required|string|min_length[5]|max_length[150]',
        'descripcion' => 'required|string|min_length[10]|max_length[2000]',
        'estado'      => 'in_list[nuevo,en_progreso,resuelto,rechazado]',
        // permit_empty: la ubicación es opcional en el formulario público;
        // sin él, insertar/actualizar con latitud/longitud en null siempre
        // fallaba la validación ("must contain only numbers").
        'latitud'     => 'permit_empty|numeric|greater_than_equal_to[-90]|less_than_equal_to[90]',
        'longitud'    => 'permit_empty|numeric|greater_than_equal_to[-180]|less_than_equal_to[180]',
        'user_id'     => 'required|integer|greater_than[0]',
        'categoria_id' => 'required|integer|greater_than[0]',
    ];

    protected $validationMessages = [
        'titulo' => [
            'required' => 'El título es obligatorio',
            'min_length' => 'El título debe tener al menos 5 caracteres',
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 10 caracteres',
        ],
    ];

    /**
     * Obtener reportes con información del usuario y categoría
     * 
     * @param int $perPage
     * @param int $page
     * @param string $estado
     * @return array
     */
    public function getReportesConDetalles(int $perPage = 10, int $page = 1, string $estado = '')
    {
        $builder = $this->select('reportes.*, usuarios.nombre, usuarios.email, categorias.nombre as categoria_nombre, categorias.color')
                        ->join('usuarios', 'usuarios.id = reportes.user_id')
                        ->join('categorias', 'categorias.id = reportes.categoria_id');

        if ($estado) {
            $builder->where('reportes.estado', $estado);
        }

        return $builder->orderBy('reportes.created_at', 'DESC')
                       ->paginate($perPage, 'default', $page);
    }

    /**
     * Obtener reportes por categoría
     * 
     * @param int $categoriaId
     * @param int $perPage
     * @return array
     */
    public function getReportesPorCategoria(int $categoriaId, int $perPage = 10)
    {
        return $this->select('reportes.*, usuarios.nombre, categorias.nombre as categoria_nombre')
                    ->join('usuarios', 'usuarios.id = reportes.user_id')
                    ->join('categorias', 'categorias.id = reportes.categoria_id')
                    ->where('reportes.categoria_id', $categoriaId)
                    ->where('reportes.estado !=', 'rechazado')
                    ->orderBy('reportes.votos_totales', 'DESC')
                    ->paginate($perPage);
    }

    /**
     * Obtener reportes por usuario
     * 
     * @param int $userId
     * @return array
     */
    public function getReportesPorUsuario(int $userId)
    {
        return $this->select('reportes.*, categorias.nombre as categoria_nombre')
                    ->join('categorias', 'categorias.id = reportes.categoria_id')
                    ->where('reportes.user_id', $userId)
                    ->orderBy('reportes.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener reportes más votados
     * 
     * @param int $limit
     * @return array
     */
    public function getMasVotados(int $limit = 5)
    {
        return $this->select('reportes.*, usuarios.nombre, categorias.nombre as categoria_nombre')
                    ->join('usuarios', 'usuarios.id = reportes.user_id')
                    ->join('categorias', 'categorias.id = reportes.categoria_id')
                    ->where('reportes.estado !=', 'rechazado')
                    ->orderBy('reportes.votos_totales', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtener reportes con filtros
     * 
     * @param array $filtros ['estado', 'categoria_id', 'barrio']
     * @param int $perPage
     * @return array
     */
    public function getFiltrados(array $filtros, int $perPage = 10)
    {
        $builder = $this->select('reportes.*, usuarios.nombre, usuarios.barrio, categorias.nombre as categoria_nombre, categorias.color, categorias.icono')
                        ->join('usuarios', 'usuarios.id = reportes.user_id')
                        ->join('categorias', 'categorias.id = reportes.categoria_id');

        if (isset($filtros['estado']) && $filtros['estado']) {
            $builder->where('reportes.estado', $filtros['estado']);
        }

        if (isset($filtros['categoria_id']) && $filtros['categoria_id']) {
            $builder->where('reportes.categoria_id', $filtros['categoria_id']);
        }

        if (isset($filtros['barrio']) && $filtros['barrio']) {
            $builder->where('usuarios.barrio', $filtros['barrio']);
        }

        return $builder->orderBy('reportes.created_at', 'DESC')
                       ->paginate($perPage);
    }

    /**
     * Barrios reales (de usuarios.barrio) que tienen al menos un reporte
     * geolocalizado, con el centroide de sus reportes para poder centrar
     * el mapa ahí. No inventa una jerarquía geográfica que la app no
     * tiene: usa el único dato de "zona" que existe de verdad.
     *
     * @return array<int, array{barrio: string, total: int, lat: float, lng: float}>
     */
    public function getBarriosConReportes(): array
    {
        return $this->select('usuarios.barrio, COUNT(*) as total, AVG(reportes.latitud) as lat, AVG(reportes.longitud) as lng')
                    ->join('usuarios', 'usuarios.id = reportes.user_id')
                    ->where('reportes.latitud IS NOT NULL')
                    ->where('reportes.longitud IS NOT NULL')
                    ->groupBy('usuarios.barrio')
                    ->orderBy('usuarios.barrio', 'ASC')
                    ->findAll();
    }

    /**
     * Incrementar votos de reporte
     * 
     * @param int $id
     * @return bool
     */
    public function incrementarVotos(int $id)
    {
        return $this->where('id', $id)->increment('votos_totales');
    }

    /**
     * Obtener estadísticas de reportes
     * 
     * @return array
     */
    public function getEstadisticas()
    {
        $total = $this->countAllResults();
        $nuevoCount = $this->where('estado', 'nuevo')->countAllResults();
        $enProgresoCount = $this->where('estado', 'en_progreso')->countAllResults();
        $resueltoCount = $this->where('estado', 'resuelto')->countAllResults();

        return [
            'total' => $total,
            'nuevo' => $nuevoCount,
            'en_progreso' => $enProgresoCount,
            'resuelto' => $resueltoCount,
        ];
    }

    /**
     * Cambiar estado de reporte
     * 
     * @param int $id
     * @param string $nuevoEstado
     * @return bool
     */
    public function cambiarEstado(int $id, string $nuevoEstado)
    {
        return $this->update($id, ['estado' => $nuevoEstado]);
    }
}
