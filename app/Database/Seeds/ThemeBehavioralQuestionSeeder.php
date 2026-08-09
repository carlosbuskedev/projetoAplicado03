<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ThemeBehavioralQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Verifica se já existem registros na tabela 'theme_behavioral_questions'
        $quantidade = $this->db->table('theme_behavioral_questions')->countAllResults();

        // Só insere se a tabela estiver vazia (quantidade == 0)
        if ($quantidade == 0) {
            $now = date('Y-m-d H:i:s');

            $this->db->table('theme_behavioral_questions')->insertBatch([
                [
                    'description' => 'Scroll Infinito',
                    'feedback' => 'O seu diagnóstico aponta que o design de "feed sem fim" capturou sua atenção automatizada. A funcionalidade de rolagem infinita elimina a sensação de que o conteúdo é finito, criando um ciclo contínuo de consumo. A dificuldade em parar não é uma falha de disciplina sua, mas uma resposta do seu cérebro à ausência de limites visuais, mantendo você constantemente online. Sua métrica definiu a sua trilha de recuperação. Siga o fluxo para quebrar esse automatismo e substituir o consumo passivo por pausas intencionais.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Gatilho de Notificações',
                    'feedback' => 'O seu diagnóstico indica uma forte reatividade aos alertas digitais. Cada notificação, seja visual ou sonora, atua no seu cérebro como um gatilho de recompensa que desperta curiosidade e libera dopamina por antecipação. É por isso que ocorre a interrupção quase incontrolável do seu foco em outras atividades. Vamos redirecionar esse ciclo. Sua trilha de recuperação vai reeducar sua atenção criando janelas de silêncio, devolvendo a você o controle sobre quando interagir com a tecnologia.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Síndrome de FoMO',
                    'feedback' => 'O seu diagnóstico revela traços da Síndrome de FoMO (Fear of Missing Out), a ansiedade gerada pelo medo de não conseguir acompanhar o que está acontecendo na vida dos outros online. Ironicamente, a preocupação excessiva em não perder os momentos virtuais resulta na perda de interações presenciais significativas na vida real. Sua trilha de recuperação focará em ancorar sua mente no momento presente, ajudando a reduzir essa ansiedade e a valorizar o "agora".',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Efeito Brain Rot',
                    'feedback' => 'O seu diagnóstico sugere uma saturação mental, muitas vezes associada ao termo "Brain Rot". O consumo excessivo de materiais online triviais ou não desafiadores compromete funções cognitivas importantes, como a memória, o raciocínio e a atenção. A sua dificuldade e impaciência para focar em tarefas simples e lentas é um reflexo direto dessa hiperconectividade. Sua trilha de recuperação ajudará a restaurar seu foco profundo por meio da reestruturação da sua atenção sustentada.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Busca por Validação',
                    'feedback' => 'O seu diagnóstico aponta uma dependência do ciclo de recompensas variáveis das redes sociais. As curtidas e comentários funcionam como um feedback imediato que gera uma sensação instantânea de validação. A imprevisibilidade de receber ou não esse engajamento aumenta a busca compulsiva, assemelhando-se à dinâmica de jogos de azar. Sua trilha trabalhará a redução dessa imprevisibilidade, ajudando você a encontrar satisfação interna sem depender da aprovação métrica da internet.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Vazio e Abstinência',
                    'feedback' => 'O seu diagnóstico indica que você está enfrentando o efeito rebote do seu sistema dopaminérgico. Após os "picos" de dopamina gerados pelo uso das redes, o cérebro passa por uma queda abrupta, criando sensações de tédio ou vazio associadas a sinais de abstinência. É exatamente isso que motiva o seu retorno rápido aos aplicativos para aliviar o desconforto. Sua trilha focará em desenvolver a tolerância a esse vazio temporário, quebrando a urgência de preenchimento digital.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Relações Líquidas',
                    'feedback' => 'O seu diagnóstico mostra que a hiperconectividade pode estar tornando suas conexões mais frágeis e efêmeras, aproximando-se do conceito de "relações líquidas". Paradoxalmente, quanto mais o indivíduo se refugia no espaço virtual em busca de pertencimento, maior pode ser a tendência ao isolamento social e ao sentimento de solidão na vida real. Sua trilha de recuperação irá encorajar o fortalecimento de vínculos presenciais e a construção de interações mais autênticas.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Poda Neural (Impulsividade)',
                    'feedback' => 'O seu diagnóstico revela um alto grau de impulsividade digital. O uso excessivo de redes sociais pode promover alterações neurológicas que favorecem respostas impulsivas, tornando a checagem das telas um comportamento automático, semelhante ao observado em vícios. O fato de você agir sem pensar não é fraqueza, mas um hábito fortemente condicionado. Sua trilha utilizará a criação de atritos para devolver a você a capacidade de pausar antes de agir.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Distorção da Realidade',
                    'feedback' => 'O seu diagnóstico aponta que as "vitrines virtuais" estão impactando sua percepção da realidade. As redes sociais frequentemente exibem fragmentos editados e aperfeiçoados da vida alheia, fomentando padrões irreais de comparação. Medir sua vida por essa realidade construída gera insatisfação e diminuição da autoestima. Sua trilha de recuperação ajudará a reestruturar sua visão, reconectando você ao seu cotidiano físico e ao bem-estar genuíno.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'description' => 'Condicionamento Pavloviano',
                    'feedback' => 'O seu diagnóstico aponta para um forte condicionamento comportamental. O design dos aplicativos é baseado no condicionamento operante para manter você engajado. Você associou contextos ou horários específicos do seu dia ao uso do celular, transformando a navegação em um reflexo quase autônomo, reforçado continuamente pela liberação de dopamina. Sua trilha focará no descondicionamento prático, quebrando esses gatilhos por meio da criação de zonas e horários livres de dispositivos.',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);

            echo "Themas das perguntas comportamentais criados com sucesso!\n";
        } else {
            echo "Os temas das perguntas comportamentais já existem no banco. Pulando Seed...\n";
        }
    }
}