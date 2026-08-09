<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BehavioralQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se já existem registros na tabela 'behavioral_questions'
        $quantidade = $this->db->table('behavioral_questions')->countAllResults();

        // Só insere se a tabela estiver vazia (quantidade == 0)
        if ($quantidade == 0) {
            $now = date('Y-m-d H:i:s');

            $this->db->table('behavioral_questions')->insertBatch([
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Você perde a noção do tempo ao deslizar o feed sem fim?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'É comum abrir o aplicativo só para olhar e passar horas rolando a tela?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'O design de rolagem infinita faz você consumir conteúdo sem pausas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Você sente dificuldade em parar de rolar o feed do TikTok ou Instagram?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Deslizar a tela indefinidamente se tornou um hábito automático?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Você rola o feed mesmo quando o conteúdo já não é mais interessante?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'O scroll infinito elimina sua sensação de que o conteúdo acabou?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Você se pega rolando a tela em momentos que deveria estar focado?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'A ação de deslizar a tela cria um ciclo contínuo de consumo na sua rotina?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 1,
                    'description' => 'Você sente que o scroll infinito consome o tempo de outras atividades?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'O som de uma notificação interrompe seu foco imediatamente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Notificações visuais despertam desejo incontrolável de abrir o app?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Você sente liberação de expectativa (dopamina) só de ouvir o celular tocar?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Notificações frequentes quebram sua concentração no trabalho?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'É difícil ignorar o alerta de uma nova mensagem ou curtida?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Cada notificação atua como um gatilho de recompensa para você?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Você mantém as notificações ativadas mesmo precisando de foco?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'A curiosidade gerada por uma notificação vence sua força de vontade?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Alertas sonoros geram ansiedade até que você verifique o aparelho?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 2,
                    'description' => 'Você interrompe tarefas importantes para checar notificações irrelevantes?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Você tem medo de perder eventos se não checar as redes sociais?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'A ansiedade de "ficar de fora" faz você acessar as redes constantemente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Você sente necessidade de acompanhar a vida dos outros online o tempo todo?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Ficar desconectado gera o temor de perder experiências importantes?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'O FoMO faz com que você permaneça mais tempo online do que deveria?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Você visita plataformas digitais apenas para garantir que não perdeu nada?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Ver os outros se divertindo online gera um sentimento de exclusão?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'A desconexão temporária traz ansiedade por não saber o que está acontecendo?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'Você prefere checar o celular a interagir com quem está ao seu lado por medo de perder algo online?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 3,
                    'description' => 'A preocupação de estar "perdendo momentos" virtuais é constante?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'O consumo de conteúdos triviais tem prejudicado seu raciocínio?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Você sente que tarefas simples, como ler um livro, tornaram-se difíceis?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Conteúdos rápidos online reduziram sua capacidade de manter a atenção?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Você nota uma "deterioração" na sua capacidade de focar em conteúdos longos?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'A hiperconectividade fragmentou sua atenção diária?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Assistir a um filme até o final sem pegar o celular é um desafio?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'O excesso de material online não desafiador está afetando sua memória?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Você tem impaciência diante de tarefas cotidianas que exigem foco lento?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Consumir vídeos curtos constantemente afeta sua clareza mental?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 4,
                    'description' => 'Você sente a mente "nebulosa" após consumir redes sociais por muito tempo?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Curtidas e comentários funcionam como validação instantânea para você?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Você apaga ou se arrepende de posts que não geram engajamento?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'A imprevisibilidade de receber likes faz você checar o app compulsivamente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'O feedback imediato nas redes dita como você se sente no dia?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Você busca validação virtual como se fosse uma máquina de jogos de azar?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'A falta de reações em uma publicação afeta sua autoestima?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Você sente prazer imediato ao ver o número de curtidas subir?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'O engajamento virtual tornou-se sua principal fonte de motivação?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Seu comportamento é reforçado unicamente pelas reações dos outros online?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 5,
                    'description' => 'Você persegue a sensação de ser validado publicamente na internet?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'Ao fechar uma rede social, você sente um vazio que o faz reabri-la em seguida?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'Momentos sem o celular geram tédio extremo ou desconforto?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'A queda de dopamina após o uso causa sensações semelhantes à abstinência?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'Você usa o celular para "anestesiar" sentimentos de sofrimento ou cansaço?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'Ficar offline traz sinais de irritabilidade ou vazio existencial?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'As redes sociais são sua principal fuga para evitar o silêncio interno?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'O prazer imediato das redes desaparece rápido, deixando insatisfação?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'O tédio é o gatilho principal para você abrir um aplicativo?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'Você retorna rapidamente às redes para aliviar o desconforto de estar sozinho?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 6,
                    'description' => 'O vazio após os "picos" de dopamina exige cada vez mais tempo de uso?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'Suas conexões virtuais são estabelecidas e rompidas de forma superficial?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'O excesso de redes sociais tem levado você ao isolamento presencial?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'Você constrói vínculos online, mas sente solidão na vida real?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'As interações presenciais significativas foram substituídas por curtidas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'É mais fácil interagir com as telas do que com as pessoas ao seu redor?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'O refúgio no espaço virtual aumentou sua tendência ao isolamento social?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'Você percebe transitoriedade e fragilidade nas suas amizades atuais?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'Conexões estabelecidas com um "clique" parecem descartáveis para você?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'Há perda de momentos na vida real devido à preocupação com o meio virtual?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 7,
                    'description' => 'O uso excessivo tem causado prejuízos nas suas relações afetivas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Você reage de forma impulsiva a estímulos digitais sem pensar?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'A dependência das redes sociais tem tornado suas ações mais automáticas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Você pega o celular no meio de conversas sem perceber que o fez?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Há dificuldade em controlar o impulso de abrir aplicativos de baixo valor cognitivo?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Você sente que perdeu o controle sobre suas respostas imediatas a alertas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'O comportamento de checagem do celular ocorre de forma quase autônoma?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Você percebe que sua paciência diminuiu drasticamente nos últimos anos?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'O uso de redes sociais parece estar fora do seu controle consciente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Você tenta estabelecer limites de uso, mas fracassa impulsivamente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 8,
                    'description' => 'Suas respostas a estímulos diários se tornaram mais reativas e imediatas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'Você compara sua vida com a "versão ideal" que os outros mostram online?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'As vitrines de bem-estar das redes sociais diminuem sua satisfação pessoal?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'Padrões irreais construídos na internet afetam sua percepção corporal?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'O uso de filtros ou imagens manipuladas cria expectativas irreais na sua vida?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'Você se prende a uma realidade inexistente performatizada nas telas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'A distorção da realidade nas redes gera insatisfação com seu cotidiano?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'A comparação constante com a vida alheia tem diminuído sua autoestima?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'Você esquece que as redes sociais representam apenas um fragmento da realidade?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'O bem-estar e a felicidade alheia online fazem sua vida parecer insuficiente?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 9,
                    'description' => 'Padrões irreais de comparação causam prejuízos emocionais reais para você?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'O hábito de acessar as redes está condicionado a situações de espera (ex: filas)?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Certos horários do dia exigem que você esteja com o celular na mão?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Estímulos sensoriais específicos sempre resultam na abertura de um app?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'O uso de redes sociais se tornou um reflexo condicionado ao acordar?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'A liberação de dopamina atua como reforçador para comportamentos automáticos diários?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Você associa imediatamente momentos de descanso ao uso de telas?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Ir ao banheiro ou deitar na cama viraram sinônimos de usar o smartphone?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Há um circuito de recompensa ativado apenas pela antecipação de abrir o app?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'A repetição do uso digital ocorre independentemente da sua vontade inicial?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'theme_behavioral_question_id' => 10,
                    'description' => 'Você utiliza o aparelho como resposta condicionada a qualquer breve momento de pausa?',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);

            echo "As perguntas comportamentais criados com sucesso!\n";
        } else {
            echo "As perguntas comportamentais já existem no banco. Pulando Seed...\n";
        }
    }
}