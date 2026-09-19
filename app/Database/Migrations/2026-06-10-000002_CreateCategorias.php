<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategorias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment'  => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
                'unique'     => true,
            ],
            'icono' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'folder',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'default'    => '#3498db',
            ],
            'descripcion' => [
                'type'  => 'TEXT',
                'null'  => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->createTable('categorias');
    }

    public function down()
    {
        $this->forge->dropTable('categorias');
    }
}
