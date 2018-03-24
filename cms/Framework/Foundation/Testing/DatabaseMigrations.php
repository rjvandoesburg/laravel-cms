<?php

namespace Cms\Framework\Foundation\Testing;

trait DatabaseMigrations
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations {
        runDatabaseMigrations as parentRunDatabaseMigrations;
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->parentRunDatabaseMigrations();

        $this->artisan('cms:migrate');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('cms:migrate:rollback');
        });
    }
}
