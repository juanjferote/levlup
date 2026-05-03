<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'             => User::factory(),
            'title'               => $this->faker->sentence(3),
            'description'         => $this->faker->optional()->sentence(8),
            'type'                => $this->faker->randomElement(['hacer', 'dejar']),
            'category'            => $this->faker->optional()->randomElement(['deporte', 'lectura', 'meditacion', 'nutricion', 'productividad']),
            'target_per_week'     => null,
            'duration_minutes'    => null,
            'active'              => true,
            'suggested_by_system' => false,
            'difficulty_level'    => null,
        ];
    }

    // estado para hábitos de hacer
    public function hacer(): static
    {
        return $this->state([
            'type'            => 'hacer',
            'target_per_week' => $this->faker->numberBetween(1, 7),
        ]);
    }

    // estado para hábitos de dejar
    public function dejar(): static
    {
        return $this->state([
            'type'            => 'dejar',
            'target_per_week' => null,
        ]);
    }
}