<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentences(3, true),
            /* @phpstan-ignore-next-line */
            'status_id' => TaskStatus::first()->id,
            /* @phpstan-ignore-next-line */
            'created_by_id' => User::inRandomOrder()->first()->id,
            /* @phpstan-ignore-next-line */
            'assigned_to_id' => User::inRandomOrder()->first()->id
        ];
    }
}
