<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePropuestas extends Migration
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
                'constraint' => ['propuesta', 'en_votacion', 'aprobada', 'rechazada'],
                'default'    => 'propuesta',
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
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', '', 'CASCADE');
        $this->forge->addKey(['estado', 'votos_totales']);
        $this->forge->createTable('propuestas');
    }

    public function down()
    {
        $this->forge->dropTable('propuestas');
    }
}
