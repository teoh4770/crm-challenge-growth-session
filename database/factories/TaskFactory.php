<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'status' => 'todo',
            'priority' => 'low',
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'project_id' => Project::factory(),
            'user_id' => User::factory(),
        ];
    }
}
