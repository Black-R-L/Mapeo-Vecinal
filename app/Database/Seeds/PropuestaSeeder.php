<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * PropuestaSeeder
 *
 * Propuestas de ejemplo. votos_totales queda en 0 acá: lo recalcula
 * VotacionSeeder a partir de los votos "a favor" que realmente inserta,
 * igual que haría la app en producción.
 */
class PropuestaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['titulo' => 'Ciclovía en la avenida principal', 'descripcion' => 'Conectaría el barrio con la estación de tren y reduciría el tránsito de autos.', 'estado' => 'en_votacion', 'user_id' => 4, 'categoria_id' => 6, 'votos_totales' => 0],
            ['titulo' => 'Nuevo parque en el terreno baldío', 'descripcion' => 'Aprovechar el lote municipal vacío para un espacio verde con juegos.', 'estado' => 'en_votacion', 'user_id' => 5, 'categoria_id' => 7, 'votos_totales' => 0],
            ['titulo' => 'Cámaras de seguridad en la plaza', 'descripcion' => 'La plaza central no tiene ninguna cámara y hubo varios robos.', 'estado' => 'en_votacion', 'user_id' => 6, 'categoria_id' => 2, 'votos_totales' => 0],
            ['titulo' => 'Huerta comunitaria en la escuela', 'descripcion' => 'Un espacio educativo y productivo para los vecinos y los chicos.', 'estado' => 'aprobada', 'user_id' => 7, 'categoria_id' => 8, 'votos_totales' => 0],
            ['titulo' => 'Ampliar la sala de salud del barrio', 'descripcion' => 'Sumar un consultorio más para reducir los tiempos de espera.', 'estado' => 'en_votacion', 'user_id' => 8, 'categoria_id' => 9, 'votos_totales' => 0],
            ['titulo' => 'Reciclaje diferenciado en las esquinas', 'descripcion' => 'Contenedores separados para papel, vidrio y orgánicos.', 'estado' => 'propuesta', 'user_id' => 4, 'categoria_id' => 10, 'votos_totales' => 0],
        ];

        $this->db->table('propuestas')->insertBatch($data);

        echo "✓ 6 propuestas de ejemplo creadas\n";
    }
}
