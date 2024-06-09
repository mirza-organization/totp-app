<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Totp>
 */
class TotpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->userName,
            'secret' => $this->faker->password,
            'issuer' => substr($this->faker->company, 0, 10)
        ];
    }
}
