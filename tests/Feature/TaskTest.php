<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    protected Mixed $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->task = Task::inRandomOrder()->first();
    }

    public function testIndex(): void
    {
        $indexUrl = route('tasks.index');
        $response = $this->get($indexUrl);
        $taskName = $this->task->name;
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testShow(): void
    {
        $showUrl = route('tasks.show', $this->task);
        $response = $this->get($showUrl);
        $taskName = $this->task->name;
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testCreate(): void
    {
        $createUrl = route('tasks.create', $this->task);

        $this->actingAs($this->user);

        $response = $this->get($createUrl);
        $response->assertOk();
    }

    public function testStore(): void
    {
        $data = Task::factory()->make()->only(
            ['name', 'description', 'status_id']
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdate(): void
    {
        /** @var Mixed $taskStatus */
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $updateUrl = route('tasks.update', $this->task);
        $taskStatusId = $taskStatus->id;
        $taskId = $this->task->id;
        $taskCreatorId = $this->task->creator->id;

        $data = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentences(5, true),
            'status_id' => $taskStatusId,
            'assigned_to_id' => $this->user->id
        ];

        $response = $this
            ->actingAs($this->user)
            ->from(route('tasks.index'))
            ->patch($updateUrl, $data);

        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('tasks', array_merge([
            'id' => $taskId,
            'created_by_id' => $taskCreatorId,
        ], $data));
    }

    public function testEdit(): void
    {
        $taskName = $this->task->name;
        $editUrl = route('tasks.edit', $this->task);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($taskName);
    }

    public function testDelete(): void
    {
        $deleteUrl = route('tasks.destroy', $this->task);

        $indexUrl = route('tasks.index');

        $this->actingAs($this->task->creator)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDeleted($this->task);
    }

    public function testDeleteByNotOwner(): void
    {
        $deleteUrl = route('tasks.destroy', $this->task);
        $notOwner = User::factory()->create();

        $indexUrl = route('tasks.index');
        $this->actingAs($notOwner)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertForbidden();

        $taskParams = $this->task->only('id', 'name');

        $this->assertDatabaseHas('tasks', $taskParams);
    }
}
