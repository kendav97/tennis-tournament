<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'skill' => fake()->numberBetween(1, 100),
            'strength' => fake()->numberBetween(1, 100),
            'speed' => fake()->numberBetween(1, 100),
            'reaction' => fake()->numberBetween(1, 100),
            'is_defeated' => 0,
        ];
    }
}
