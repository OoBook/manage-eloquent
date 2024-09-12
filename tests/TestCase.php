<?php

namespace Oobook\Database\Eloquent\Tests;

use Illuminate\Database\Schema\Blueprint;
use Oobook\Database\Eloquent\ManageEloquentServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Note: this also flushes the cache from within the migration
        $this->setUpDatabase($this->app);

    }

    protected function getPackageProviders($app)
    {
      return [
        ManageEloquentServiceProvider::class,
      ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('cache.prefix', 'spatie_tests---');
        $app['config']->set('cache.default', getenv('CACHE_DRIVER') ?: 'array');

    }

    /**
     * Set up the database.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function setUpDatabase($app)
    {
        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->softDeletes();
        });

        $schema->create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $schema->create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->uuidMorphs('fileable');
            $table->string('name');
        });

    }
}
