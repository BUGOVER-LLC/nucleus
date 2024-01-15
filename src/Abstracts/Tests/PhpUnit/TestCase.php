<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Tests\PhpUnit;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Nucleus\Traits\TestCaseTrait;
use Nucleus\Traits\TestsTraits\PhpUnit\TestsAuthHelperTrait;
use Nucleus\Traits\TestsTraits\PhpUnit\TestsMockHelperTrait;
use Nucleus\Traits\TestsTraits\PhpUnit\TestsRequestHelperTrait;
use Nucleus\Traits\TestsTraits\PhpUnit\TestsResponseHelperTrait;

abstract class TestCase extends LaravelTestCase
{
    use TestCaseTrait;
    use TestsRequestHelperTrait;
    use TestsResponseHelperTrait;
    use TestsMockHelperTrait;
    use TestsAuthHelperTrait;
    use LazilyRefreshDatabase;

    /**
     * The base URL to use while testing the application.
     */
    protected string $baseUrl;

    /**
     * Seed the DB on migrations
     */
    protected bool $seed = true;

    /**
     * Setup the test environment, before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Reset the test environment, after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Refresh the in-memory database.
     */
    protected function refreshInMemoryDatabase(): void
    {
        $this->artisan('migrate', $this->migrateUsing());

        // Install Passport Client for Testing
        $this->setupPassportOAuth2();

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Refresh a conventional test database.
     */
    protected function refreshTestDatabase(): void
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', $this->migrateFreshUsing());
            $this->setupPassportOAuth2();

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
