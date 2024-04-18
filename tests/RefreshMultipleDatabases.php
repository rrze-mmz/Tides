<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait RefreshMultipleDatabases
{
    use DatabaseMigrations {
        DatabaseMigrations::runDatabaseMigrations as originalRunDatabaseMigrations;
    }

    /**
     * Run the database migrations for the test environment.
     */
    public function runDatabaseMigrations()
    {
        $this->runMigrations('sqlite');
        $this->runMigrations('sqlite_stats');

        $this->beforeApplicationDestroyed(function () {
            $this->runRollbacks('sqlite');
            $this->runRollbacks('sqlite_stats');
        });

        RefreshDatabaseState::$migrated = true;
    }

    /**
     * Handle running migrations for a specific database.
     *
     * @param  string  $database  The name of the database connection to use.
     */
    protected function runMigrations($database)
    {
        Artisan::call('migrate', [
            '--database' => $database,
            '--path' => 'database/migrations',
            '--realpath' => true,
        ]);

        // To prevent the database from being dropped, keep the connection alive.
        DB::connection($database)->getPdo();
    }

    /**
     * Handle rolling back migrations for a specific database.
     *
     * @param  string  $database  The name of the database connection to use.
     */
    protected function runRollbacks($database)
    {
        Artisan::call('migrate:rollback', [
            '--database' => $database,
            '--path' => 'database/migrations',
            '--realpath' => true,
        ]);
    }
}
