<?php

namespace Ryancco\HasUuidRouteKey\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Ryancco\HasUuidRouteKey\Tests\Mocks\MockModel;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
        $this->setupRoute();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'uuid_models');
        $app['config']->set('database.connections.uuid_models', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    private function setupDatabase(): void
    {
        Schema::create('mock_models', static function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->timestamps();
        });
    }

    private function setupRoute(): void
    {
        Route::middleware('bindings')->get('mock/{model}', static function (MockModel $model) {
            return $model;
        })->name('mock');
    }
}
