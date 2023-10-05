<?php

namespace Tests;

use App\Console\Kernel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;
    use WithFaker;
     /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @return User
     */
    protected function prepareUser():User
    {
        return User::factory()->create();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function makeAuthHeader(User $user): array
    {
        return [
            "token" => "Bearer ".auth()->login($user)
        ];
    }

    /**
     * @return array
     */
    protected function getSuccessAndCreatedCode(): array
    {
        return [
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
        ];
    }

     /**
     * @return array
     */
    protected function getSuccessAndUpdatedCode(): array
    {
        return [
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'User Updated successfully!',
            ]
        ];
    }

}
