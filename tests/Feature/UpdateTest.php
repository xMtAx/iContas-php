<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTest extends TestCase
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
     * Teste deve editar uma despesa com todos os campos corretos
     *
     * @return void
     */
    public function testShouldUpdateExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider();

        // Pegar dados do usuário
        $user = $data['users'][0];
        $todo = $data['expenses'][$user->id][0];
        $this->actingAs($user);

        // Criar dados para a requisição
        $input = [
            'title' => $this->faker->sentence(2),
            'value' => $this->faker->randomFloat(2, 1, 100),
            'due_date' => date('Y-m-d', strtotime('+10 days'))
        ];

        // Acessar rota de edição de despesas
        $this->put('/expenses/' . $todo->id, $input);

        // Verificar se despesa foi editada no banco de dados
        $this->assertDatabaseHas('expenses', [
            'id' => $todo->id,
            'title' => $input['title'],
            'value' => $input['value'],
            'due_date' => $input['due_date']
        ]);
    }
}
