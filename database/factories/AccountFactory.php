<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
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
            'brand' => $this->faker->randomElement(['BNI', 'BCA', 'BSI', 'DANA', 'BRI', 'OVO']),
            'account_number' => $this->faker->bankAccountNumber,
            'account_name' => $this->faker->company,
            'account_type' => $this->faker->randomElement(['checking', 'savings', 'credit']),
            'balance' => $this->faker->numberBetween(10000, 10000000),
        ];        
    }
}
