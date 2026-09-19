<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PropuestaModel
 * 
 * Gestiona propuestas de mejoras para el barrio
 * Incluye sistema de votación integrado
 * 
 * @package App\Models
 */
class PropuestaModel extends Model
{
    protected $table            = 'propuestas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['titulo', 'descripcion', 'estado', 'user_id', 'categoria_id', 'votos_totales'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'titulo'      => 'required|string|min_length[5]|max_length[150]',
        'descripcion' => 'required|string|min_length[10]|max_length[2000]',
        'estado'      => 'in_list[propuesta,en_votacion,aprobada,rechazada]',
        'user_id'     => 'required|integer|greater_than[0]',
        'categoria_id' => 'required|integer|greater_than[0]',
    ];

    /**
     * Obtener propuestas con detalles
     * 
     * @param int $perPage
     * @param int $page
     * @param string $estado
     * @return array
     */
    public function getPropuestasConDetalles(int $perPage = 10, int $page = 1, string $estado = '')
    {
        $builder = $this->select('propuestas.*, usuarios.nombre, usuarios.email, categorias.nombre as categoria_nombre, categorias.color, categorias.icono')
                        ->join('usuarios', 'usuarios.id = propuestas.user_id')
                        ->join('categorias', 'categorias.id = propuestas.categoria_id');

        if ($estado) {
            $builder->where('propuestas.estado', $estado);
        }

        return $builder->orderBy('propuestas.votos_totales', 'DESC')
                       ->orderBy('propuestas.created_at', 'DESC')
                       ->paginate($perPage, 'default', $page);
    }

    /**
     * Obtener propuestas por usuario
     * 
     * @param int $userId
     * @return array
     */
    public function getPropuestasPorUsuario(int $userId)
    {
        return $this->select('propuestas.*, categorias.nombre as categoria_nombre')
                    ->join('categorias', 'categorias.id = propuestas.categoria_id')
                    ->where('propuestas.user_id', $userId)
                    ->orderBy('propuestas.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener propuestas en votación
     * 
     * @param int $perPage
     * @return array
     */
    public function getEnVotacion(int $perPage = 10)
    {
        return $this->select('propuestas.*, usuarios.nombre, categorias.nombre as categoria_nombre')
                    ->join('usuarios', 'usuarios.id = propuestas.user_id')
                    ->join('categorias', 'categorias.id = propuestas.categoria_id')
                    ->where('propuestas.estado', 'en_votacion')
                    ->orderBy('propuestas.votos_totales', 'DESC')
                    ->paginate($perPage);
    }

    /**
     * Obtener propuestas más votadas
     * 
     * @param int $limit
     * @return array
     */
    public function getMasVotadas(int $limit = 10)
    {
        return $this->select('propuestas.*, usuarios.nombre, categorias.nombre as categoria_nombre, categorias.color, categorias.icono')
                    ->join('usuarios', 'usuarios.id = propuestas.user_id')
                    ->join('categorias', 'categorias.id = propuestas.categoria_id')
                    ->where('propuestas.estado', 'en_votacion')
                    ->orderBy('propuestas.votos_totales', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtener propuestas por categoría
     * 
     * @param int $categoriaId
     * @param int $perPage
     * @return array
     */
    public function getPropuestasPorCategoria(int $categoriaId, int $perPage = 10)
    {
        return $this->select('propuestas.*, usuarios.nombre, categorias.nombre as categoria_nombre')
                    ->join('usuarios', 'usuarios.id = propuestas.user_id')
                    ->join('categorias', 'categorias.id = propuestas.categoria_id')
                    ->where('propuestas.categoria_id', $categoriaId)
                    ->orderBy('propuestas.votos_totales', 'DESC')
                    ->paginate($perPage);
    }

    /**
     * Incrementar votos de propuesta
     * 
     * @param int $id
     * @return bool
     */
    public function incrementarVotos(int $id)
    {
        return $this->where('id', $id)->increment('votos_totales');
    }

    /**
     * Decrementar votos de propuesta (al eliminar un voto a favor)
     *
     * @param int $id
     * @return bool
     */
    public function decrementarVotos(int $id)
    {
        return $this->where('id', $id)->decrement('votos_totales');
    }

    /**
     * Cambiar estado de propuesta
     * 
     * @param int $id
     * @param string $nuevoEstado
     * @return bool
     */
    public function cambiarEstado(int $id, string $nuevoEstado)
    {
        return $this->update($id, ['estado' => $nuevoEstado]);
    }

    /**
     * Obtener estadísticas de propuestas
     * 
     * @return array
     */
    public function getEstadisticas()
    {
        $total = $this->countAllResults();
        $enVotacionCount = $this->where('estado', 'en_votacion')->countAllResults();
        $aprobadaCount = $this->where('estado', 'aprobada')->countAllResults();
        $rechazadaCount = $this->where('estado', 'rechazada')->countAllResults();

        return [
            'total' => $total,
            'en_votacion' => $enVotacionCount,
            'aprobada' => $aprobadaCount,
            'rechazada' => $rechazadaCount,
        ];
    }

    /**
     * Activar votación para propuesta
     * 
     * @param int $id
     * @return bool
     */
    public function activarVotacion(int $id)
    {
        return $this->update($id, ['estado' => 'en_votacion']);
    }
}
