<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * VotacionModel
 * 
 * Gestiona votos en propuestas
 * Implementa lógica de votación única por usuario-propuesta
 * 
 * @package App\Models
 */
class VotacionModel extends Model
{
    protected $table            = 'votaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'propuesta_id', 'tipo_voto'];
    protected $useTimestamps    = true;
    protected $createdField     = 'fecha';
    // La tabla no tiene updated_at (los votos no se "actualizan", solo se
    // crean o eliminan). Dejarlo con el valor por defecto rompía todo
    // insert()/update() con "Unknown column 'updated_at' in field list".
    protected $updatedField     = '';

    protected $validationRules  = [
        'user_id'      => 'required|integer|greater_than[0]',
        'propuesta_id' => 'required|integer|greater_than[0]',
        'tipo_voto'    => 'required|in_list[favor,contra]',
    ];

    /**
     * Verificar si usuario ya votó en propuesta
     * 
     * @param int $userId
     * @param int $propuestaId
     * @return bool
     */
    public function yaVoto(int $userId, int $propuestaId)
    {
        $voto = $this->where('user_id', $userId)
                     ->where('propuesta_id', $propuestaId)
                     ->first();

        return $voto ? true : false;
    }

    /**
     * Obtener voto específico
     * 
     * @param int $userId
     * @param int $propuestaId
     * @return array|null
     */
    public function getVoto(int $userId, int $propuestaId)
    {
        return $this->where('user_id', $userId)
                    ->where('propuesta_id', $propuestaId)
                    ->first();
    }

    /**
     * Cambiar voto de usuario
     * 
     * @param int $userId
     * @param int $propuestaId
     * @param string $tipoVoto
     * @return bool
     */
    public function cambiarVoto(int $userId, int $propuestaId, string $tipoVoto)
    {
        $voto = $this->where('user_id', $userId)
                     ->where('propuesta_id', $propuestaId)
                     ->first();

        if ($voto) {
            return $this->update($voto['id'], ['tipo_voto' => $tipoVoto]);
        }

        return false;
    }

    /**
     * Obtener votos por propuesta
     * 
     * @param int $propuestaId
     * @return array
     */
    public function getVotosPorPropuesta(int $propuestaId)
    {
        return $this->select('tipo_voto, COUNT(*) as cantidad')
                    ->where('propuesta_id', $propuestaId)
                    ->groupBy('tipo_voto')
                    ->findAll();
    }

    /**
     * Contar votos a favor
     * 
     * @param int $propuestaId
     * @return int
     */
    public function contarVotosAfavor(int $propuestaId)
    {
        return $this->where('propuesta_id', $propuestaId)
                    ->where('tipo_voto', 'favor')
                    ->countAllResults();
    }

    /**
     * Contar votos en contra
     * 
     * @param int $propuestaId
     * @return int
     */
    public function contarVotosEnContra(int $propuestaId)
    {
        return $this->where('propuesta_id', $propuestaId)
                    ->where('tipo_voto', 'contra')
                    ->countAllResults();
    }

    /**
     * Obtener votos del usuario
     * 
     * @param int $userId
     * @return array
     */
    public function getVotosDelUsuario(int $userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Obtener votantes por propuesta
     * 
     * @param int $propuestaId
     * @return array
     */
    public function getVotantesPorPropuesta(int $propuestaId)
    {
        return $this->select('votaciones.*, usuarios.nombre, usuarios.email')
                    ->join('usuarios', 'usuarios.id = votaciones.user_id')
                    ->where('votaciones.propuesta_id', $propuestaId)
                    ->findAll();
    }

    /**
     * Eliminar voto (cambiar voto)
     * 
     * @param int $userId
     * @param int $propuestaId
     * @return bool
     */
    public function eliminarVoto(int $userId, int $propuestaId)
    {
        return $this->where('user_id', $userId)
                    ->where('propuesta_id', $propuestaId)
                    ->delete();
    }

    /**
     * Obtener resumen de votación
     * 
     * @param int $propuestaId
     * @return array
     */
    public function getResumenVotacion(int $propuestaId)
    {
        $favor = $this->contarVotosAfavor($propuestaId);
        $contra = $this->contarVotosEnContra($propuestaId);
        $total = $favor + $contra;
        $porcentajeAfavor = $total > 0 ? round(($favor / $total) * 100, 2) : 0;

        return [
            'favor' => $favor,
            'contra' => $contra,
            'total' => $total,
            'porcentaje_favor' => $porcentajeAfavor,
        ];
    }
}
