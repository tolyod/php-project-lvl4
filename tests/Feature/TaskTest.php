<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testIndex(): void
    {
        $task = Task::inRandomOrder()->first();
        $indexUrl = route('tasks.index');
        $response = $this->get($indexUrl);
        /* @phpstan-ignore-next-line */
        $taskName = $task->name;
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testShow(): void
    {
        $task = Task::inRandomOrder()->first();
        $showUrl = route('tasks.show', $task);
        $response = $this->get($showUrl);
        /* @phpstan-ignore-next-line */
        $taskName = $task->name;
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testCreate(): void
    {
        $task = Task::inRandomOrder()->first();
        $createUrl = route('tasks.create', $task);

        $this->actingAs($this->user);

        $response = $this->get($createUrl);
        $response->assertOk();
    }

    public function testStore(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $storeUrl = route('tasks.store');
        /* @phpstan-ignore-next-line */
        $taskStatusId = $taskStatus->id;

        $this
            ->actingAs($this->user)
            ->from(route('tasks.index'));

        $data = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentences(5, true),
            'status_id' => $taskStatusId,
        ];
        $response = $this->post($storeUrl, $data);
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('tasks', array_merge($data, ['created_by_id' => $this->user->id]));
    }

    public function testUpdate(): void
    {
        $task = Task::inRandomOrder()->first();
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $updateUrl = route('tasks.update', $task);
        /* @phpstan-ignore-next-line */
        $taskStatusId = $taskStatus->id;
        /* @phpstan-ignore-next-line */
        $taskId = $task->id;

        /* @phpstan-ignore-next-line */
        $taskCreatorId = $task->creator->id;

        $this
            ->actingAs($this->user)
            ->from(route('tasks.index'));

        $data = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentences(5, true),
            'status_id' => $taskStatusId,
            'assigned_to_id' => $this->user->id
        ];
        $response = $this->patch($updateUrl, $data);
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('tasks', array_merge([
            'id' => $taskId,
            'created_by_id' => $taskCreatorId,
        ], $data));
    }

    public function testEdit(): void
    {
        $task = Task::inRandomOrder()->first();
        /* @phpstan-ignore-next-line */
        $taskName = $task->name;
        $editUrl = route('tasks.edit', $task);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testDelete(): void
    {
        $task = Task::inRandomOrder()->first();
        $deleteUrl = route('tasks.destroy', $task);

        $indexUrl = route('tasks.index');

        /* @phpstan-ignore-next-line */
        $this->actingAs($task->creator)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        /* @phpstan-ignore-next-line */
        $this->assertDeleted($task);
    }

    public function testDeleteByNotOwner(): void
    {
        $task = Task::inRandomOrder()->first();
        $deleteUrl = route('tasks.destroy', $task);
        $notOwner = User::factory()->create();

        $indexUrl = route('tasks.index');
        $this->actingAs($notOwner)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertForbidden();

        /* @phpstan-ignore-next-line */
        $taskParams = $task->only('id', 'name');

        $this->assertDatabaseHas('tasks', $taskParams);
    }
}
