<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'pergunta' => 'O cadastro é gratuito?',
                'resposta' => 'Sim. O cadastro na plataforma é gratuito. Os recursos adicionais estão disponíveis nos planos pagos.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'O Planeta Boy intermedia os serviços?',
                'resposta' => 'Não. O Planeta Boy atua apenas como plataforma de anúncios e não participa das negociações.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Quem pode anunciar?',
                'resposta' => 'Somente pessoas maiores de 18 anos, de acordo com os Termos de Uso da plataforma.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Posso alterar minhas fotos e descrição?',
                'resposta' => 'Sim. Os anunciantes podem atualizar seus perfis conforme os recursos disponíveis em cada plano.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Quanto tempo leva para meu anúncio aparecer?',
                'resposta' => 'Após a análise e aprovação pela equipe de moderação, o perfil é publicado.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Como faço para destacar meu perfil?',
                'resposta' => 'Assinando um dos planos pagos ou adquirindo recursos adicionais de destaque.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Posso cancelar meu plano?',
                'resposta' => 'Sim. O cancelamento pode ser solicitado a qualquer momento, permanecendo ativo até o término do período contratado.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'O site garante clientes?',
                'resposta' => 'Não. O Planeta Boy oferece visibilidade aos anunciantes, mas não garante contatos ou resultados financeiros.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Como denunciar um perfil?',
                'resposta' => 'Cada perfil possui ferramentas de denúncia para que situações suspeitas sejam analisadas pela moderação.',
                'categoria' => 'geral',
            ],
            [
                'pergunta' => 'Meus dados ficam protegidos?',
                'resposta' => 'Sim. O tratamento das informações segue as diretrizes da legislação aplicável e da Política de Privacidade da plataforma.',
                'categoria' => 'geral',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['pergunta' => $faq['pergunta']],
                [
                    'resposta' => $faq['resposta'],
                    'categoria' => $faq['categoria'],
                    'ativo' => true,
                ]
            );
        }

        $this->command->info('Importadas ' . count($faqs) . ' FAQs do DOCX.');
    }
}
