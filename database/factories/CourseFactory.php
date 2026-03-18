<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'created_by'  => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => 'published',
            'is_mandatory' => fake()->boolean(30),
            'passing_score' => 60,
            'max_attempts' => 3,
            'exam_duration_minutes' => 30,
            'start_date' => now()->subDays(7),
            'end_date' => now()->addDays(30),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function mandatory(): static
    {
        return $this->state(['is_mandatory' => true]);
    }
}
