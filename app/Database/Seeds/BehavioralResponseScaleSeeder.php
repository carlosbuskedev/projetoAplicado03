<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BehavioralResponseScaleSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se já existem registros na tabela 'behavioral_responses_scale'
        $quantidade = $this->db->table('behavioral_responses_scale')->countAllResults();

        // Só insere se a tabela estiver vazia (quantidade == 0)
        if ($quantidade == 0) {
            $now = date('Y-m-d H:i:s');

            $this->db->table('behavioral_responses_scale')->insertBatch([
                [
                    'score' => 1,
                    'description' => 'Nunca',
                    'frequency' => 'nenhum dia',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'score' => 2,
                    'description' => 'Raramente',
                    'frequency' => '1 a 2 dias na semana',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'score' => 3,
                    'description' => 'Às vezes',
                    'frequency' => '3 a 4 dias na semana',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'score' => 4,
                    'description' => 'Frequentemente',
                    'frequency' => '5 a 6 dias na semana',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'score' => 5,
                    'description' => 'Sempre',
                    'frequency' => 'todos os dias',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);

            echo "Scala de respostas comportamentais criadas com sucesso!\n";
        } else {
            echo "Scala de respostas comportamentais já existem no banco. Pulando Seed...\n";
        }
    }
}