<?php

namespace Database\Factories;

use App\Models\CourseSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseSchedule>
 */
class CourseScheduleFactory extends Factory
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
            'course_name' => fake()->words(2, true),
            'lecturer' => fake()->name(),
            'day' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            'starts_at' => '08:00:00',
            'ends_at' => '10:00:00',
            'room' => 'Ruang A101',
        ];
    }
}
