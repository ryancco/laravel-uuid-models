<?php

namespace Ryancco\HasUuidRouteKey\Tests;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ryancco\HasUuidRouteKey\Tests\Mocks\Post;

class HasUuidRouteKeyTest extends TestCase
{
    public function testMakesModelsRoutableByUuid(): void
    {
        $model = Post::create();

        $this->get(route('posts.view', $model))
            ->assertOk()
            ->assertJson($model->toArray());
    }

    public function testGeneratesRoutesWithTheUuidAttribute(): void
    {
        $model = Post::create();

        $this->assertStringContainsString($model->uuid->toString(), route('posts.view', $model));
    }

    public function testGeneratesValidUuidAttributeWhenModelIsCreated(): void
    {
        $model = Post::create();

        $this->assertTrue(Uuid::isValid($model->uuid));
    }

    public function testGeneratesValidUuidAttributeWhenModelIsSavedAfterConstructorInstantiation(): void
    {
        $model = new Post();
        $model->save();

        $this->assertTrue(Uuid::isValid($model->uuid));
    }

    public function testDoesNotGenerateUuidWhenModelIsInstantiatedByConstructor(): void
    {
        $model = new Post();

        $this->assertEmpty($model->uuid);
    }

    public function testGetsUuidAttributeAsUuidInterface(): void
    {
        $model = Post::create();

        $this->assertInstanceOf(UuidInterface::class, $model->uuid);
    }

    public function testSetsUuidAttributeFromString(): void
    {
        $uuid = Str::orderedUuid();

        $model = new Post();
        $model->uuid = $uuid->toString();

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testSetsUuidAttributeFromUuidInterface(): void
    {
        $uuid = Str::orderedUuid();

        $model = new Post();
        $model->uuid = $uuid;

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testDoesNotOverwriteUuidIfItIsAlreadySet(): void
    {
        $uuid = Str::orderedUuid();
        $model = new Post(compact('uuid'));

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testDoesNotOverwriteUuidWhenHydratingTheModel(): void
    {
        $model = Post::create();
        $hydrated = Post::find($model->id);

        $this->assertEquals($model->uuid->toString(), $hydrated->uuid->toString());
    }

    public function testDoesNotOverwriteUuidWhenSavingTheModel(): void
    {
        $uuid = Str::orderedUuid();
        $model = new Post(compact('uuid'));

        $model->save();
        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }
}
