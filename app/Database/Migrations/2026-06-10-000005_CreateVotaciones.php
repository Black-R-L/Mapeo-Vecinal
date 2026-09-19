<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateVotaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment'  => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'propuesta_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'tipo_voto' => [
                'type'       => 'ENUM',
                'constraint' => ['favor', 'contra'],
                'default'    => 'favor',
            ],
            'fecha' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('propuesta_id', 'propuestas', 'id', '', 'CASCADE');
        $this->forge->addUniqueKey(['user_id', 'propuesta_id'], 'unique_voto');
        $this->forge->createTable('votaciones');
    }

    public function down()
    {
        $this->forge->dropTable('votaciones');
    }
}
