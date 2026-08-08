<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se já existem registros na tabela 'users'
        $quantidade = $this->db->table('users')->countAllResults();

        // Só insere se a tabela estiver vazia (quantidade == 0)
        if ($quantidade == 0) {
            $now = date('Y-m-d H:i:s');

            $this->db->table('users')->insertBatch([
                [
                    'name'       => 'Administrador',
                    'email'      => 'admin@exemplo.com',
                    'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                    'role'       => 'admin',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name'       => 'Usuário Comum',
                    'email'      => 'user@exemplo.com',
                    'password'   => password_hash('user123', PASSWORD_DEFAULT),
                    'role'       => 'user',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            echo "Usuários criados com sucesso!\n";
        } else {
            echo "Os usuários já existem no banco. Pulando Seed...\n";
        }
    }
}
