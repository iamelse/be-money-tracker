<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

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

    /**
     * State to mock transactions for the authenticated user.
     */
    public function forAuthenticatedUser()
    {
        return $this->state(function () {
            $user = Auth::user();
            // Get the user's account IDs
            $accountIds = $user->accounts()->pluck('id');

            return [
                'user_id' => $user->id,
                'account_id' => $this->faker->randomElement($accountIds),
            ];
        });
    }
}