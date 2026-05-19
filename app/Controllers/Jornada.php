<?php

namespace App\Controllers;

class Jornada extends BaseController
{
    public function index(): string
    {
        $arrCards = [
            ['titulo' => 'Estudar React', 'descricao' => 'Aprender componentes e hooks', 'tempo' => 25],
            ['titulo' => 'Exercícios Físicos', 'descricao' => 'Treino completo do dia', 'tempo' => 30],
            ['titulo' => 'Meditação', 'descricao' => 'Sessão de mindfulness', 'tempo' => 15],
            ['titulo' => 'Leitura', 'descricao' => 'Ler um capítulo novo', 'tempo' => 45],
            ['titulo' => 'Projeto Pessoal', 'descricao' => 'Desenvolver nova feature', 'tempo' => 50],
            ['titulo' => 'Idiomas', 'descricao' => 'Praticar conversação', 'tempo' => 20]
        ];

        return view('jornada', ['cards' => $arrCards]);
    }
}
