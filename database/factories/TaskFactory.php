<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'title'        => $this->faker->sentence(3),
            'description'  => $this->faker->optional()->sentence(8),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+7 days'),
            'completed'    => false,
        ];
    }
}