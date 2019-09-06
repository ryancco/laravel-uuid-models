<?php

namespace Ryancco\HasUuidRouteKey\Tests;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ryancco\HasUuidRouteKey\Tests\Mocks\MockModel;

class HasUuidRouteKeyTest extends TestCase
{
    public function testMakesModelsRoutableByUuid(): void
    {
        $model = MockModel::create();

        $this->get(route('mock', $model))
            ->assertOk()
            ->assertJson($model->toArray());
    }

    public function testGeneratesRoutesWithTheUuidAttribute(): void
    {
        $model = MockModel::create();

        $this->assertStringContainsString($model->uuid->toString(), route('mock', $model));
    }

    public function testGeneratesValidUuidAttributeWhenModelIsCreated(): void
    {
        $model = MockModel::create();

        $this->assertTrue(Uuid::isValid($model->uuid));
    }

    public function testGeneratesValidUuidAttributeWhenModelIsSavedAfterConstructorInstantiation(): void
    {
        $model = new MockModel();
        $model->save();

        $this->assertTrue(Uuid::isValid($model->uuid));
    }

    public function testDoesNotGenerateUuidWhenModelIsInstantiatedByConstructor(): void
    {
        $model = new MockModel();

        $this->assertEmpty($model->uuid);
    }

    public function testGetsUuidAttributeAsUuidInterface(): void
    {
        $model = MockModel::create();

        $this->assertInstanceOf(UuidInterface::class, $model->uuid);
    }

    public function testSetsUuidAttributeFromString(): void
    {
        $uuid = Str::orderedUuid();

        $model = new MockModel();
        $model->uuid = $uuid->toString();

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testSetsUuidAttributeFromUuidInterface(): void
    {
        $uuid = Str::orderedUuid();

        $model = new MockModel();
        $model->uuid = $uuid;

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testDoesNotOverwriteUuidIfItIsAlreadySet(): void
    {
        $uuid = Str::orderedUuid();
        $model = new MockModel(compact('uuid'));

        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }

    public function testDoesNotOverwriteUuidWhenHydratingTheModel(): void
    {
        $model = MockModel::create();
        $hydrated = MockModel::find($model->id);

        $this->assertEquals($model->uuid->toString(), $hydrated->uuid->toString());
    }

    public function testDoesNotOverwriteUuidWhenSavingTheModel(): void
    {
        $uuid = Str::orderedUuid();
        $model = new MockModel(compact('uuid'));

        $model->save();
        $this->assertEquals($uuid->toString(), $model->uuid->toString());
    }
}
