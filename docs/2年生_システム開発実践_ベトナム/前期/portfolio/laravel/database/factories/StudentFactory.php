<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->name(),                      // ランダムな名前
            'email' => $this->faker->unique()->safeEmail(),        // ランダムなメール
            'score' => $this->faker->numberBetween(0, 100),        // 0〜100のランダムな点数
        ];
    }
}
