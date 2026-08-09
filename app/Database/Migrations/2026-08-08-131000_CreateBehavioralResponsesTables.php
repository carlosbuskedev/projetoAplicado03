<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBehavioralResponsesTables extends Migration
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
            'score' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->createTable('behavioral_responses_scale');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'behavioral_questions_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'behavioral_responses_scale_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'week' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
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
        $this->forge->addForeignKey('behavioral_questions_id', 'behavioral_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('behavioral_responses_scale_id', 'behavioral_responses_scale', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('behavioral_responses');
    }

    public function down(): void
    {
        $this->forge->dropTable('behavioral_responses', true);
        $this->forge->dropTable('behavioral_responses_scale', true);
    }
}
