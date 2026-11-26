<?php

namespace Database\Factories;

use App\Models\FinanceTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinanceTransaction>
 */
class FinanceTransactionFactory extends Factory
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
            'type' => 'expense',
            'category' => 'Makanan',
            'description' => 'Makan siang',
            'amount' => 25000,
            'date' => now()->toDateString(),
        ];
    }
}
