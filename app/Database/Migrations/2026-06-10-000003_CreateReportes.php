<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment'  => true,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['nuevo', 'en_progreso', 'resuelto', 'rechazado'],
                'default'    => 'nuevo',
            ],
            'latitud' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
            ],
            'longitud' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'categoria_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'votos_totales' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
            'deleted_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', '', 'CASCADE');
        $this->forge->addKey(['estado', 'created_at']);
        $this->forge->createTable('reportes');
    }

    public function down()
    {
        $this->forge->dropTable('reportes');
    }
}
