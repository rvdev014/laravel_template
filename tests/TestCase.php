<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\Helpers\TestHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use TestHelper;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Mail::fake();

        // run rbac seeder
        $this->seed();
    }

    public static function authDataProvider(): array
    {
        return [
            'authorized' => [true],
            'unauthorized' => [false],
        ];
    }
}
