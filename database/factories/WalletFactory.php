<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => fn () => Profile::factory()->create()->id,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'balance' => $this->faker->numberBetween(0, 1000),
            'currency' => $this->faker->currencyCode
        ];
    }
}
