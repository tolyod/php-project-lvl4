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
        $indexUrl = route('labels.index');
        $response = $this->get($indexUrl);
        $response
            ->assertOk()
            ->assertSee($labelName);
    }

    public function testCreate(): void
    {
        $createUrl = route('labels.create', $this->label);

        $this->actingAs($this->user);

        $response = $this->get($createUrl);
        $response->assertOk();
    }

    public function testStore(): void
    {
        $indexUrl = route('labels.index');
        $storeUrl = route('labels.store');
        $name = $this->faker->word;
        $description = $this->faker->realText(60, 2);

        $this
            ->actingAs($this->user)
            ->from($indexUrl);

        $response = $this->post(
            $storeUrl,
            [
                'name' => $name,
                'description' => $description
            ]
        );
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas(
            'labels',
            [
                'name' => $name,
                'description' => $description
            ]
        );
    }

    public function testUpdate(): void
    {
        $label = $this->label;
        $indexUrl = route('labels.index');
        $updateUrl = route('labels.update', $label);
        $name = $this->faker->word;
        $description = $this->faker->realText(60, 2);

        $response = $this
            ->actingAs($this->user)
            ->from($indexUrl)
            ->patch(
                $updateUrl,
                [
                    'name' => $name,
                    'description' => $description
                ]
            );
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('labels', ['name' => $name]);
    }

    public function testEdit(): void
    {
        $label = $this->label;
        /* @phpstan-ignore-next-line */
        $labelName = $this->label->name;
        $editUrl = route('labels.edit', $label);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($labelName);
    }

    public function testDelete(): void
    {
        $deleteUrl = route('labels.destroy', $this->label);

        $indexUrl = route('labels.index');
        $this->actingAs($this->user)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        /* @phpstan-ignore-next-line */
        $this->assertDeleted($this->label);
    }
}
