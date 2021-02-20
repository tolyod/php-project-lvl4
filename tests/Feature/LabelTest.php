<?php

namespace Tests\Feature;

use App\Models\Label;
use Tests\TestCase;

class LabelTest extends TestCase
{
    public function testIndex(): void
    {
        $label = Label::inRandomOrder()->first();
        $indexUrl = route('labels.index');
        $response = $this->get($indexUrl);
        $response
            ->assertOk()
            ->assertSee($label->name);
    }

    public function testCreate(): void
    {
        $label = Label::inRandomOrder()->first();
        $createUrl = route('labels.create', $label);

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
        $label = Label::inRandomOrder()->first();
        $indexUrl = route('labels.index');
        $updateUrl = route('labels.update', $label);
        $name = $this->faker->word;
        $description = $this->faker->realText(60, 2);

        $this
            ->actingAs($this->user)
            ->from($indexUrl);

        $response = $this->patch(
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
        $label = Label::inRandomOrder()->first();
        $editUrl = route('labels.edit', $label);

        $this->actingAs($this->user);

        $response = $this->get($editUrl);
        $response
            ->assertOk()
            ->assertSee($label->name);
    }

    public function testDelete(): void
    {
        $label = Label::inRandomOrder()->first();
        $deleteUrl = route('labels.destroy', $label);

        $indexUrl = route('labels.index');
        $this->actingAs($this->user)->from($indexUrl);

        $response = $this->delete($deleteUrl);
        $response->assertRedirect($indexUrl);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDeleted($label);
    }
}
