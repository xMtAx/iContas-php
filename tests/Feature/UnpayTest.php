<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnpayTest extends TestCase
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
     * Teste deve pagar uma despesa
     *
     * @return void
     */
    public function testShouldPayExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider();

        // Pegar dados do usuário
        $user = $data['users'][0];
        $expense = $data['expenses'][$user->id][0];
        $this->actingAs($user);

        // Acessar rota completar depesa
        $this->get('/expenses/pay/' . $expense->id);

        // Verificar se despesa foi paga no banco de dados
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'paid' => true
        ]);
    }

    /**
     * Teste deve remover pagamento de uma despesa
     *
     * @return void
     */
    public function testShouldUnpayExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider();

        // Pegar dados do usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Pagar primeira despesa
        $expense = $data['expenses'][$user->id][0];
        $expense->paid = true;
        $expense->save();

        // Acessar rota completar depesa
        $this->get('/expenses/unpay/' . $expense->id);

        // Verificar se despesa foi paga no banco de dados
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'paid' => false
        ]);
    }
}
