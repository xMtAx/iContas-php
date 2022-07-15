<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(2),
            'value' => $this->faker->randomFloat(2, 1, 100),
            'paid' => false,
            'due_date' => date('Y-m-d', strtotime('+1 days')),
            'user_id' => User::factory()->create()->id
        ];
    }

    /**
     * Indicate that the model's is_complete should be true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state(function () {
            return [
                'paid' => true
            ];
        });
    }
}
