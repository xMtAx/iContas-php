<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTest extends TestCase
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
     * Teste deve remover uma despesa
     *
     * @return void
     */
    public function testShouldDeleteExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider();

        // Pegar dados do usuário
        $user = $data['users'][0];
        $expense = $data['expenses'][$user->id][0];
        $this->actingAs($user);

        // Acessar rota completar expenses
        $this->delete('/expenses/' . $expense->id);

        // Verificar se despesa foi removida no banco de dados
        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id
        ]);
    }
}
