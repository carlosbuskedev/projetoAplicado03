<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJourneysTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'quest_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'started_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'completed_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'interruptions_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'remaining_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('quest_id', 'quests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('journeys');
    }

    public function down(): void
    {
        $this->forge->dropTable('journeys', true);
    }
}
