<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Work',
                'Personal',
                'Shopping',
                'Health',
                'Finance',
                'Education',
                'Family',
                'Travel',
                'Hobbies',
                'Home',
            ]),
            'color' => fake()->hexColor(),
            'user_id' => User::factory(),
        ];
    }
}

