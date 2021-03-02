<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    /**
     * @var TaskStatus|null $taskStatus
     * */
    private $taskStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskStatus = TaskStatus::inRandomOrder()->first();
    }

    public function testIndex(): void
    {
        $indexUrl = route('task_statuses.index');
        $response = $this->get($indexUrl);
        /* @phpstan-ignore-next-line */
        $taskStatusName = $this->taskStatus->name;
        $response
            ->assertOk()
            ->assertSee($taskStatusName);
    }

    public function testStore(): void
    {
        $indexUrl = route('task_statuses.index');
        $storeUrl = route('task_statuses.store');
        $name = $this->faker->word;

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->post($storeUrl, ['name' => $name]);
        $response->assertRedirect($indexUrl);

        $this->assertDatabaseHas('task_statuses', ['name' => $name]);
    }

    public function testStoreByGuest(): void
    {
        $taskStatus = $this->taskStatus;
        $storeUrl = route('task_statuses.store', $taskStatus);
        $loginUrl = route('login');
        $name = $this->faker->word;

        $response = $this->post($storeUrl, ['name' => $name]);
        $response->assertRedirect($loginUrl);

        $this->assertGuest();
        $this->assertDatabaseMissing('task_statuses', ['name' => $name]);
    }

    public function testUpdate(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $indexUrl = route('task_statuses.index');
        $storeUrl = route('task_statuses.update', $taskStatus);
        $name = $this->faker->word;

        $this
            ->actingAs($this->user)
            ->from($indexUrl);

        $response = $this->patch($storeUrl, ['name' => $name]);
        $response->assertRedirect($indexUrl);

        $this->assertDatabaseHas('task_statuses', ['name' => $name]);
    }

    public function testUpdateByGuest(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $updateUrl = route('task_statuses.update', $taskStatus);
        $loginUrl = route('login');
        $name = $this->faker->word;

        $response = $this->patch($updateUrl, ['name' => $name]);
        $response->assertRedirect($loginUrl);

        $this->assertGuest();
        $this->assertDatabaseMissing('task_statuses', ['name' => $name]);
    }

    public function testEdit(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        /* @phpstan-ignore-next-line */
        $taskStatusName = $taskStatus->name;
        $editUrl = route('task_statuses.edit', $taskStatus);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($taskStatusName);
    }

    public function testEditByGuest(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $editUrl = route('task_statuses.edit', $taskStatus);
        $loginUrl = route('login');

        $response = $this->get($editUrl);

        $this->assertGuest();
        $response->assertRedirect($loginUrl);
    }

    public function testDelete(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $deleteUrl = route('task_statuses.destroy', $taskStatus);

        $indexUrl = route('task_statuses.index');
        $this->actingAs($this->user)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);

        /* @phpstan-ignore-next-line */
        $this->assertDeleted($taskStatus);
    }

    public function testDeleteByGuest(): void
    {
        $taskStatus = TaskStatus::inRandomOrder()->first();
        $deleteUrl = route('task_statuses.destroy', $taskStatus);

        $loginUrl = route('login');

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($loginUrl);

        /* @phpstan-ignore-next-line */
        $this->assertDatabaseHas('task_statuses', $taskStatus->only('id', 'name'));
    }
}
