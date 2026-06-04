<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuestsTable extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'short_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'estimated_time' => [
                'type' => 'TIME',
            ],
            'difficulty' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high'],
                'default'    => 'medium',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['to_do', 'in_progress', 'done'],
                'default'    => 'to_do',
            ],
            'deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'experience' => [
                'type'       => 'INT',
                'constraint' => 11,
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
        $this->forge->createTable('quests');
    }

    public function down(): void
    {
        $this->forge->dropTable('quests');
    }
}
