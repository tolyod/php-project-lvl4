<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    private TaskStatus $taskStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskStatus = optional(TaskStatus::notNewInRandomOrder())->first();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $taskStatusName = $this->taskStatus->name;
        $response
            ->assertOk()
            ->assertSee($taskStatusName);
    }

    public function testStore(): void
    {
        $indexUrl = route('task_statuses.index');
        $data = TaskStatus::factory()->make()->only('name');

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->post(route('task_statuses.store'), $data);
        $response->assertRedirect($indexUrl);

        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testStoreByGuest(): void
    {
        $taskStatus = $this->taskStatus;
        $storeUrl = route('task_statuses.store', $taskStatus);
        $data = TaskStatus::factory()->make()->only('name');

        $response = $this->post($storeUrl, $data);
        $response->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseMissing('task_statuses', $data);
    }

    public function testUpdate(): void
    {
        $indexUrl = route('task_statuses.index');
        $storeUrl = route('task_statuses.update', $this->taskStatus);
        $data = TaskStatus::factory()->make()->only('name');

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->patch($storeUrl, $data);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testUpdateByGuest(): void
    {
        $updateUrl = route('task_statuses.update', $this->taskStatus);
        $loginUrl = route('login');
        $data = TaskStatus::factory()->make()->only('name');

        $response = $this->patch($updateUrl, $data);
        $response->assertRedirect($loginUrl);

        $this->assertGuest();
        $this->assertDatabaseMissing('task_statuses', $data);
    }

    public function testEdit(): void
    {
        $taskStatusName = $this->taskStatus->name;
        $editUrl = route('task_statuses.edit', $this->taskStatus);

        $response = $this
            ->actingAs($this->user)
            ->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($taskStatusName);
    }

    public function testEditByGuest(): void
    {
        $editUrl = route('task_statuses.edit', $this->taskStatus);

        $response = $this->get($editUrl);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function testDelete(): void
    {
        $deleteUrl = route('task_statuses.destroy', $this->taskStatus);
        $indexUrl = route('task_statuses.index');

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDeleted($this->taskStatus);
    }

    public function testDeleteByGuest(): void
    {
        $deleteUrl = route('task_statuses.destroy', $this->taskStatus);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('task_statuses', $this->taskStatus->only('id', 'name'));
    }
}
