<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * VotacionSeeder
 *
 * Votos de ejemplo sobre las propuestas 1-5 (la propuesta 6 todavía no
 * entró en votación). Al final recalcula propuestas.votos_totales
 * contando solo los votos "a favor", igual que hace la app en runtime.
 */
class VotacionSeeder extends Seeder
{
    public function run()
    {
        $votos = [
            // Ciclovía (propuesta 1): 4 a favor
            ['user_id' => 5, 'propuesta_id' => 1, 'tipo_voto' => 'favor'],
            ['user_id' => 6, 'propuesta_id' => 1, 'tipo_voto' => 'favor'],
            ['user_id' => 7, 'propuesta_id' => 1, 'tipo_voto' => 'favor'],
            ['user_id' => 8, 'propuesta_id' => 1, 'tipo_voto' => 'favor'],
            // Parque (propuesta 2): 3 a favor, 1 en contra
            ['user_id' => 4, 'propuesta_id' => 2, 'tipo_voto' => 'favor'],
            ['user_id' => 6, 'propuesta_id' => 2, 'tipo_voto' => 'favor'],
            ['user_id' => 7, 'propuesta_id' => 2, 'tipo_voto' => 'favor'],
            ['user_id' => 5, 'propuesta_id' => 2, 'tipo_voto' => 'contra'],
            // Cámaras (propuesta 3): 2 a favor, 3 en contra
            ['user_id' => 4, 'propuesta_id' => 3, 'tipo_voto' => 'favor'],
            ['user_id' => 5, 'propuesta_id' => 3, 'tipo_voto' => 'favor'],
            ['user_id' => 6, 'propuesta_id' => 3, 'tipo_voto' => 'contra'],
            ['user_id' => 7, 'propuesta_id' => 3, 'tipo_voto' => 'contra'],
            ['user_id' => 8, 'propuesta_id' => 3, 'tipo_voto' => 'contra'],
            // Huerta comunitaria (propuesta 4, ya aprobada): 4 a favor
            ['user_id' => 5, 'propuesta_id' => 4, 'tipo_voto' => 'favor'],
            ['user_id' => 6, 'propuesta_id' => 4, 'tipo_voto' => 'favor'],
            ['user_id' => 7, 'propuesta_id' => 4, 'tipo_voto' => 'favor'],
            ['user_id' => 8, 'propuesta_id' => 4, 'tipo_voto' => 'favor'],
            // Sala de salud (propuesta 5): 5 a favor, unanime
            ['user_id' => 4, 'propuesta_id' => 5, 'tipo_voto' => 'favor'],
            ['user_id' => 5, 'propuesta_id' => 5, 'tipo_voto' => 'favor'],
            ['user_id' => 6, 'propuesta_id' => 5, 'tipo_voto' => 'favor'],
            ['user_id' => 7, 'propuesta_id' => 5, 'tipo_voto' => 'favor'],
            ['user_id' => 8, 'propuesta_id' => 5, 'tipo_voto' => 'favor'],
        ];

        $this->db->table('votaciones')->insertBatch($votos);

        // votos_totales solo cuenta los votos "a favor" (misma regla que la app).
        $this->db->query(
            'UPDATE propuestas p
             SET votos_totales = (
                 SELECT COUNT(*) FROM votaciones v
                 WHERE v.propuesta_id = p.id AND v.tipo_voto = "favor"
             )'
        );

        echo "✓ 22 votos de ejemplo creados y contadores recalculados\n";
    }
}
