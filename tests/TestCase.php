<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function tearDown(): void
    {
        if ($this->app && $this->app->bound('db')) {
            try {
                while ($this->app['db']->connection()->transactionLevel() > 0) {
                    $this->app['db']->rollBack();
                }
            } catch (\Throwable $e) {
                // Ignore cleanup errors
            }
        }

        parent::tearDown();
    }
}
