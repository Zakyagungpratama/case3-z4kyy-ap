<?php

namespace Database\Factories;

use App\Models\StudyTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyTask>
 */
class StudyTaskFactory extends Factory
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
            'title' => fake()->sentence(4),
            'course_name' => fake()->words(2, true),
            'deadline' => now()->addDays(3),
            'priority' => 'Sedang',
            'is_completed' => false,
        ];
    }
}
