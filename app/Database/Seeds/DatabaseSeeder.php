<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Orquestador: `php spark db:seed DatabaseSeeder` siembra todo en un
 * solo paso, en el orden correcto (usuarios/categorías antes que los
 * datos que las referencian).
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UsuarioSeeder');
        $this->call('CategoriaSeeder');
        $this->call('ReporteSeeder');
        $this->call('PropuestaSeeder');
        $this->call('VotacionSeeder');
    }
}
