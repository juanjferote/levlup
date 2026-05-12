<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'            => $this->faker->unique()->word(),
            'description'     => $this->faker->sentence(),
            'icon'            => '🏆',
            'rarity'          => $this->faker->randomElement(['comun', 'rara', 'epica', 'legendaria']),
            'category'        => null,
            'condition_type'  => 'tasks_completed',
            'condition_value' => 1,
        ];
    }
}