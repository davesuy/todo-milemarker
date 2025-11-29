<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        $isCompleted = fake()->boolean(20);

        return [
            'title' => fake()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
            'user_id' => User::factory(),
            'category_id' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function incomplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-7 days', '-1 day'),
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }
}

