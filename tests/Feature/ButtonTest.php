<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ButtonTest extends TestCase
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
     * Teste deve abrir página de login e ver o botão com a classe correta
     *
     * @return void
     */
    public function testOpenLoginPageAndSeeCorrectButton()
    {
        // Acessar a rota /
        $response = $this->get('/login');

        // Verificar se aparece a classe 'btn_2'
        $response->assertSee('btn_2');
    }

    /**
     * Teste deve abrir página de registro e ver o botão com a classe correta
     *
     * @return void
     */
    public function testOpenRegisterPageAndSeeCorrectButton()
    {
        // Acessar a rota /
        $response = $this->get('/register');

        // Verificar se aparece a classe 'btn_2'
        $response->assertSee('btn_2');
    }
}
