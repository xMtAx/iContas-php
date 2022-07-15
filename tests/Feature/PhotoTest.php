<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PhotoTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * Fornecer dados prontos para os testes
     *
     * @param int $numUsers
     * @param int $numExpenses
     *
     * @return array
     */
    public function dataProvider(int $numUsers = 1, int $numExpenses = 1) {
        $faker = app(Generator::class);

        $users = [];
        $expenses = [];
        for ($i = 0; $i < $numUsers; $i++) {
            // Criar usuário
            $user = User::factory()->create();

            $expenses[$user->id] = [];

            // Criar expenses
            for ($j = 0; $j < $numExpenses; $j++) {
                $expenses[$user->id][] = Expense::factory()->create(['user_id' => $user->id]);
            }

            $users[] = $user;
        }

        return compact('users', 'expenses');
    }

    /**
     * Teste deve abrir página inicial e ver a imagem correta
     *
     * @return void
     */
    public function testOpenLandingPageAndSeeCorrectPhoto()
    {
        // Acessar a rota /
        $response = $this->get('/');

        // Verificar se aparece a classe 'woman-2.jpg'
        $response->assertSee('woman-2.jpg');
    }
}
