<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * ReporteSeeder
 *
 * Reportes de ejemplo geolocalizados (zona de Buenos Aires) para que el
 * mapa público no nazca vacío. Referencia los usuarios/categorías ya
 * sembrados por UsuarioSeeder/CategoriaSeeder.
 */
class ReporteSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['titulo' => 'Bache grande en la esquina', 'descripcion' => 'Pozo profundo que ya dañó varios autos, cerca del cruce principal.', 'estado' => 'nuevo', 'latitud' => -34.6037, 'longitud' => -58.3816, 'user_id' => 4, 'categoria_id' => 1, 'votos_totales' => 5],
            ['titulo' => 'Luminaria apagada hace 2 semanas', 'descripcion' => 'La calle queda muy oscura de noche, riesgo para peatones.', 'estado' => 'en_progreso', 'latitud' => -34.6090, 'longitud' => -58.3850, 'user_id' => 5, 'categoria_id' => 3, 'votos_totales' => 8],
            ['titulo' => 'Basural a cielo abierto', 'descripcion' => 'Vecinos tiran escombros en el terreno baldío de la esquina.', 'estado' => 'nuevo', 'latitud' => -34.6002, 'longitud' => -58.3790, 'user_id' => 6, 'categoria_id' => 4, 'votos_totales' => 3],
            ['titulo' => 'Semáforo intermitente sin reparar', 'descripcion' => 'Genera confusión y casi provoca choques en hora pico.', 'estado' => 'nuevo', 'latitud' => -34.6070, 'longitud' => -58.3900, 'user_id' => 7, 'categoria_id' => 2, 'votos_totales' => 12],
            ['titulo' => 'Caño roto perdiendo agua', 'descripcion' => 'Hace tres días que corre agua potable por la vereda.', 'estado' => 'resuelto', 'latitud' => -34.5980, 'longitud' => -58.3760, 'user_id' => 8, 'categoria_id' => 5, 'votos_totales' => 6],
            ['titulo' => 'Parada de colectivo sin techo', 'descripcion' => 'El refugio se rompió con el temporal y no lo repusieron.', 'estado' => 'nuevo', 'latitud' => -34.6120, 'longitud' => -58.3830, 'user_id' => 4, 'categoria_id' => 6, 'votos_totales' => 4],
            ['titulo' => 'Plaza con juegos rotos', 'descripcion' => 'Los hamacas están rotas y hay vidrios en el arenero.', 'estado' => 'en_progreso', 'latitud' => -34.6015, 'longitud' => -58.3920, 'user_id' => 5, 'categoria_id' => 7, 'votos_totales' => 9],
            ['titulo' => 'Vereda rota frente a la escuela', 'descripcion' => 'Baldosas levantadas, riesgo de caída para los chicos.', 'estado' => 'nuevo', 'latitud' => -34.6055, 'longitud' => -58.3780, 'user_id' => 6, 'categoria_id' => 8, 'votos_totales' => 15],
            ['titulo' => 'Falta personal en la salita de salud', 'descripcion' => 'Las esperas superan las 3 horas para una consulta simple.', 'estado' => 'nuevo', 'latitud' => -34.6100, 'longitud' => -58.3770, 'user_id' => 7, 'categoria_id' => 9, 'votos_totales' => 20],
            ['titulo' => 'Contenedor de basura desbordado', 'descripcion' => 'No lo vacían desde hace una semana y ya generó olores.', 'estado' => 'rechazado', 'latitud' => -34.5995, 'longitud' => -58.3860, 'user_id' => 8, 'categoria_id' => 10, 'votos_totales' => 2],
        ];

        $this->db->table('reportes')->insertBatch($data);

        echo "✓ 10 reportes de ejemplo creados\n";
    }
}
