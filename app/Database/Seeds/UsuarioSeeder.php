<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * UsuarioSeeder
 * 
 * Crea usuarios iniciales para prueba
 * - 1 admin
 * - 2 autoridades
 * - 5 ciudadanos
 */
class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin
            [
                'nombre'   => 'Administrador',
                'email'    => 'admin@mapeo-vecinal.local',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'rol'      => 'admin',
                'barrio'   => 'Centro',
                'estado'   => 'activo',
            ],
            // Autoridades
            [
                'nombre'   => 'Juan García (Autoridad)',
                'email'    => 'autoridad1@municipio.local',
                'password' => password_hash('autoridad123', PASSWORD_BCRYPT),
                'rol'      => 'autoridad',
                'barrio'   => 'Centro',
                'estado'   => 'activo',
            ],
            [
                'nombre'   => 'María López (Autoridad)',
                'email'    => 'autoridad2@municipio.local',
                'password' => password_hash('autoridad123', PASSWORD_BCRYPT),
                'rol'      => 'autoridad',
                'barrio'   => 'Norte',
                'estado'   => 'activo',
            ],
            // Ciudadanos
            [
                'nombre'   => 'Carlos Rodríguez',
                'email'    => 'carlos@ejemplo.com',
                'password' => password_hash('ciudadano123', PASSWORD_BCRYPT),
                'rol'      => 'ciudadano',
                'barrio'   => 'Centro',
                'estado'   => 'activo',
            ],
            [
                'nombre'   => 'Ana Martínez',
                'email'    => 'ana@ejemplo.com',
                'password' => password_hash('ciudadano123', PASSWORD_BCRYPT),
                'rol'      => 'ciudadano',
                'barrio'   => 'Norte',
                'estado'   => 'activo',
            ],
            [
                'nombre'   => 'Luis González',
                'email'    => 'luis@ejemplo.com',
                'password' => password_hash('ciudadano123', PASSWORD_BCRYPT),
                'rol'      => 'ciudadano',
                'barrio'   => 'Sur',
                'estado'   => 'activo',
            ],
            [
                'nombre'   => 'Elena Díaz',
                'email'    => 'elena@ejemplo.com',
                'password' => password_hash('ciudadano123', PASSWORD_BCRYPT),
                'rol'      => 'ciudadano',
                'barrio'   => 'Este',
                'estado'   => 'activo',
            ],
            [
                'nombre'   => 'Francisco Torres',
                'email'    => 'francisco@ejemplo.com',
                'password' => password_hash('ciudadano123', PASSWORD_BCRYPT),
                'rol'      => 'ciudadano',
                'barrio'   => 'Oeste',
                'estado'   => 'activo',
            ],
        ];

        // Insertar todos los registros
        $this->db->table('usuarios')->insertBatch($data);

        echo "✓ 8 usuarios creados exitosamente\n";
        echo "  - admin@mapeo-vecinal.local (Contraseña: admin123)\n";
        echo "  - autoridad1@municipio.local (Contraseña: autoridad123)\n";
        echo "  - autoridad2@municipio.local (Contraseña: autoridad123)\n";
        echo "  - carlos@ejemplo.com (Contraseña: ciudadano123)\n";
        echo "  - ana@ejemplo.com (Contraseña: ciudadano123)\n";
        echo "  - luis@ejemplo.com (Contraseña: ciudadano123)\n";
        echo "  - elena@ejemplo.com (Contraseña: ciudadano123)\n";
        echo "  - francisco@ejemplo.com (Contraseña: ciudadano123)\n";
    }
}
