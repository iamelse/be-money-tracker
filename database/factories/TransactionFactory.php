<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'transaction_type' => $this->faker->randomElement(['debit', 'credit']),
            'amount' => $this->faker->numberBetween(10000, 200000),
            'transaction_date' => $this->faker->date(),
            'category' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
