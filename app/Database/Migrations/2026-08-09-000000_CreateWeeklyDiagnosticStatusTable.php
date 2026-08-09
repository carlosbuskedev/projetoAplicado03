<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWeeklyDiagnosticStatusTable extends Migration
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
            'users_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'week' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'day' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'completed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
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
        $this->forge->addForeignKey('users_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('weekly_diagnostic_status');
    }

    public function down(): void
    {
        $this->forge->dropTable('weekly_diagnostic_status', true);
    }
}
