<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Faker\Generator;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExpenseTest extends TestCase
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
     * Teste não deve entrar na página dashboard sem autenticação
     *
     * @return void
     */
    public function testShouldNotOpenDashboardWhenUserUnauthorized()
    {
        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se usuário foi redirecionado para o login
        $response->assertRedirect('/login');
    }

    /**
     * Teste deve abrir página inicial e ver a palavra 'jeito'
     *
     * @return void
     */
    public function testOpenLandingPageAndSeeCorrectSpelling()
    {
        // Acessar a rota /
        $response = $this->get('/');

        // Verificar se aparece a palavra 'jeito'
        $response->assertSee('jeito');

        // Verificar se não aparece a palavra 'geito'
        $response->assertDontSee('geito');
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

    /**
     * Teste deve abrir dashboard e ver a palavra 'iConta$'
     *
     * @return void
     */
    public function testOpenDashboardAndSeeTitle()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 3);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece a frase 'iConta$'
        $response->assertSee('iConta$');
    }

    /**
     * Teste usuários não deve ver despesas de outros usuários
     *
     * @return void
     */
    public function testUserShouldNotSeeExpensesFromOtherUsers()
    {
        // Criar dados falsos
        $data = $this->dataProvider(2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $expense = $data['expenses'][$user->id][0];
        $this->actingAs($user);

        // Pegar dados do segundo usuário
        $user2 = $data['users'][1];
        $expense2 = $data['expenses'][$user2->id][0];

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece o título da despesa do usuário
        $response->assertSee($expense->title);

        // Verificar se não aparece o título da despesa do outro usuário
        $response->assertDontSee($expense2->title);
    }

    /**
     * Teste deve abrir dashboard e ver despesa paga
     *
     * @return void
     */
    public function testOpenDashboardAndSeePaidExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Pagar primeira despesa
        $expense = $data['expenses'][$user->id][0];
        $expense->paid = true;
        $expense->save();

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece a frase 'pago'
        $response->assertSee('pago');
    }

    /**
     * Teste deve abrir dashboard e não ver despesa paga
     *
     * @return void
     */
    public function testOpenDashboardAndNotSeePaidExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece a frase 'pago'
        $response->assertDontSee('pago');
    }

    /**
     * Teste deve abrir dashboard e ver despesa vencida
     *
     * @return void
     */
    public function testOpenDashboardAndSeeExpiredExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Trocar data da primeira despesa
        $expense = $data['expenses'][$user->id][0];
        $expense->due_date = date('Y-m-d', strtotime('-1 days'));
        $expense->save();

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece a frase 'vencido'
        $response->assertSee('vencido');
    }

    /**
     * Teste deve abrir dashboard e não ver despesa vencida
     *
     * @return void
     */
    public function testOpenDashboardAndNotSeeExpiredExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Acessar a rota /dashboard
        $response = $this->get('/dashboard');

        // Verificar se aparece a frase 'vencido'
        $response->assertDontSee('vencido');
    }

    /**
     * Teste deve armazenar uma despesa com todos os campos corretos
     *
     * @return void
     */
    public function testShouldStoreExpense()
    {
        // Criar dados falsos
        $data = $this->dataProvider(1, 0);

        // Pegar dados do usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Criar dados para a requisição
        $input = [
            'title' => $this->faker->sentence(2),
            'value' => $this->faker->randomFloat(2, 1, 100),
            'due_date' => date('Y-m-d', strtotime('+1 days'))
        ];

        // Acessar rota de criação de depesas
        $this->post('/expenses', $input);

        // Verificar se despesa foi criada no banco de dados
        $this->assertDatabaseHas('expenses', [
            'title' => $input['title'],
            'value' => $input['value'],
            'due_date' => $input['due_date'],
            'user_id' => $user->id
        ]);
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

    /**
     * Teste deve acessar página de edição
     *
     * @return void
     */
    public function testShouldOpenEditPage()
    {
        // Criar dados falsos
        $data = $this->dataProvider(2);

        // Pegar dados do usuário
        $user = $data['users'][0];
        $expense = $data['expenses'][$user->id][0];
        $this->actingAs($user);

        // Acessar a rota /expenses/{expense}/edit
        $response = $this->get('/expenses/edit/' . $expense->id);

        // Verificar se aparece o título da despesa a ser editado
        $response->assertSee($expense->title);
    }

    /**
     * Teste usuários não deve ver página de edição para depesesas de outros usuários
     *
     * @return void
     */
    public function testUserShouldNotEditExpensesFromOtherUsers()
    {
        // Criar dados falsos
        $data = $this->dataProvider(2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $this->actingAs($user);

        // Pegar dados do segundo usuário
        $user2 = $data['users'][1];
        $expense2 = $data['expenses'][$user2->id][0];

        // Acessar a rota /expenses/{expense}/edit
        $response = $this->get('/expenses/edit/' . $expense2->id);

        // Verificar se página é 404
        $response->assertNotFound();
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

    /**
     * Teste não deve editar uma despesa quando o usuário não está autorizado
     *
     * @return void
     */
    public function testShouldNotUpdateExpenseWhenUserUnauthorized()
    {
        // Criar dados falsos
        $data = $this->dataProvider(2);

        // Pegar dados do primeiro usuário
        $user = $data['users'][0];
        $expense = $data['expenses'][$user->id][0];

        // Pegar dados do segundo usuário
        $user2 = $data['users'][1];
        $this->actingAs($user2);

        // Criar dados para a requisição
        $input = [
            'title' => $this->faker->sentence(2),
            'value' => $this->faker->randomFloat(2, 1, 100),
            'due_date' => date('Y-m-d', strtotime('+10 days'))
        ];

        // Acessar rota de edição de despesas
        $response = $this->put('/expenses/' . $expense->id, $input);

        // Verificar se requisição foi proíbida
        $response->assertForbidden();

        // Verificar se despesa não foi editada
        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
            'title' => $input['title'],
            'value' => $input['value'],
            'due_date' => $input['due_date']
        ]);
    }
}
