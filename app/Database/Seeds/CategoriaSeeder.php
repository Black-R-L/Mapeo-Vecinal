<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * CategoriaSeeder
 * 
 * Crea categorías iniciales de reportes y propuestas
 */
class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'      => 'Infraestructura',
                'icono'       => 'fa-road',
                'color'       => '#e74c3c',
                'descripcion' => 'Problemas con calles, vías y estructuras',
            ],
            [
                'nombre'      => 'Seguridad',
                'icono'       => 'fa-shield',
                'color'       => '#9b59b6',
                'descripcion' => 'Reportes sobre seguridad pública',
            ],
            [
                'nombre'      => 'Alumbrado',
                'icono'       => 'fa-lightbulb',
                'color'       => '#f39c12',
                'descripcion' => 'Iluminación pública dañada o faltante',
            ],
            [
                'nombre'      => 'Limpieza',
                'icono'       => 'fa-broom',
                'color'       => '#16a085',
                'descripcion' => 'Espacios públicos sucios o contaminados',
            ],
            [
                'nombre'      => 'Servicios Públicos',
                'icono'       => 'fa-water',
                'color'       => '#3498db',
                'descripcion' => 'Agua, gas, energía eléctrica',
            ],
            [
                'nombre'      => 'Transporte',
                'icono'       => 'fa-bus',
                'color'       => '#2980b9',
                'descripcion' => 'Problemas con transporte público',
            ],
            [
                'nombre'      => 'Espacios Verdes',
                'icono'       => 'fa-leaf',
                'color'       => '#27ae60',
                'descripcion' => 'Parques, jardines y áreas verdes',
            ],
            [
                'nombre'      => 'Educación',
                'icono'       => 'fa-graduation-cap',
                'color'       => '#8e44ad',
                'descripcion' => 'Mejoras en educación y espacios educativos',
            ],
            [
                'nombre'      => 'Salud',
                'icono'       => 'fa-hospital',
                'color'       => '#c0392b',
                'descripcion' => 'Servicios y espacios de salud',
            ],
            [
                'nombre'      => 'Otros',
                'icono'       => 'fa-question-circle',
                'color'       => '#7f8c8d',
                'descripcion' => 'Otros problemas o propuestas',
            ],
        ];

        // Insertar todas las categorías
        $this->db->table('categorias')->insertBatch($data);

        echo "✓ 10 categorías creadas exitosamente\n";
        echo "  - Infraestructura\n";
        echo "  - Seguridad\n";
        echo "  - Alumbrado\n";
        echo "  - Limpieza\n";
        echo "  - Servicios Públicos\n";
        echo "  - Transporte\n";
        echo "  - Espacios Verdes\n";
        echo "  - Educación\n";
        echo "  - Salud\n";
        echo "  - Otros\n";
    }
}
