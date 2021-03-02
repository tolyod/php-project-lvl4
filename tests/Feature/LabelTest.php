<?php

namespace Tests\Feature;

use App\Models\Label;
use Tests\TestCase;

class LabelTest extends TestCase
{
    /**
    * @var Label|null $label
    * */
    protected $label;

    public function setUp(): void
    {
        parent::setUp();
        $this->label = Label::inRandomOrder()->first();
    }
    public function testIndex(): void
    {
        /* @phpstan-ignore-next-line */
        $labelName = $this->label->name;
        $response = $this->get(route('labels.index'));
        $response
            ->assertOk()
            ->assertSee($labelName);
    }

    public function testCreate(): void
    {
        $createUrl = route('labels.create', $this->label);

        $response = $this->actingAs($this->user)->get($createUrl);
        $response->assertOk();
    }

    public function testStore(): void
    {
        $indexUrl = route('labels.index');
        $storeUrl = route('labels.store');
        $data = Label::factory()->make()->only(['name', 'description']);

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->post($storeUrl, $data);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdate(): void
    {
        $indexUrl = route('labels.index');
        $updateUrl = route('labels.update', $this->label);
        $data = Label::factory()->make()->only(['name', 'description']);

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->patch($updateUrl, $data);

        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('labels', $data);
    }

    public function testEdit(): void
    {
        $label = $this->label;
        /* @phpstan-ignore-next-line */
        $labelName = $this->label->name;
        $editUrl = route('labels.edit', $label);

        $response = $this->actingAs($this->user)->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($labelName);
    }

    public function testDelete(): void
    {
        $deleteUrl = route('labels.destroy', $this->label);
        $indexUrl = route('labels.index');

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        /* @phpstan-ignore-next-line */
        $this->assertDeleted($this->label);
    }
}
