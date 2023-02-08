<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clearCache();

        $this->user = User::factory()->create([
            'name' => 'Auth User Test',
            'email' => 'authusertest@test.com',
            'password' => Hash::make('secret')
        ]);
    }

    protected function clearCache()
    {
        $commands = [
            'route:clear',
            'view:clear',
            'config:clear',
            'cache:clear',
        ];

        foreach ($commands as $command) {
            Artisan::call($command);
        }
    }
}
