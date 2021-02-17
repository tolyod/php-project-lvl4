<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testIndex()
    {
        $task = Task::inRandomOrder()->first();
        $indexUrl = route('tasks.index');
        $response = $this->get($indexUrl);
        $response
            ->assertOk()
            ->assertSee($task->name);
    }

    public function testShow()
    {
        $task = Task::inRandomOrder()->first();
        $showUrl = route('tasks.show', $task);
        $response = $this->get($showUrl);
        $response
            ->assertOk()
            ->assertSee($task->name);
    }

    public function testCreate()
    {
        $task = Task::inRandomOrder()->first();
        $createUrl = route('tasks.create', $task);

        $this->actingAs($this->user);

        $response = $this->get($createUrl);
        $response->assertOk();
    }

    public function testStore()
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $storeUrl = route('tasks.store');

        $this
            ->actingAs($this->user)
            ->from(route('tasks.index'));

        $data = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentences(5, true),
            'status_id' => $taskStatus->id,
        ];
        $response = $this->post($storeUrl, $data);
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('tasks', array_merge($data, ['created_by_id' => $this->user->id]));
    }

    public function testUpdate()
    {
        $task = Task::inRandomOrder()->first();
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $updateUrl = route('tasks.update', $task);

        $this
            ->actingAs($this->user)
            ->from(route('tasks.index'));

        $data = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentences(5, true),
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $this->user->id
        ];
        $response = $this->patch($updateUrl, $data);
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('tasks', array_merge([
            'id' => $task->id,
            'created_by_id' => $task->creator->id,
        ], $data));
    }

    public function testEdit()
    {
        $task = Task::inRandomOrder()->first();
        $editUrl = route('tasks.edit', $task);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($task->name);
    }

    public function testDelete()
    {
        $task = Task::inRandomOrder()->first();
        $deleteUrl = route('tasks.destroy', $task);

        $indexUrl = route('tasks.index');
        $this->actingAs($task->creator)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDeleted($task);
    }

    public function testDeleteByNotOwner()
    {
        $task = Task::inRandomOrder()->first();
        $deleteUrl = route('tasks.destroy', $task);
        $notOwner = User::factory()->create();

        $indexUrl = route('tasks.index');
        $this->actingAs($notOwner)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertForbidden();

        $this->assertDatabaseHas('tasks', $task->only('id', 'name'));
    }
}
