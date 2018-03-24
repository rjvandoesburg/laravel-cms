<?php

namespace Cms\Framework\Foundation\Providers;

use Cms\Framework\Database\Console\Migrations\MigrateCommand;
use Cms\Framework\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\ServiceProvider;
use Cms\Framework\Database\Console\Migrations\ResetCommand as MigrateResetCommand;
use Cms\Framework\Database\Console\Migrations\StatusCommand as MigrateStatusCommand;
use Cms\Framework\Database\Console\Migrations\InstallCommand as MigrateInstallCommand;
use Cms\Framework\Database\Console\Migrations\RefreshCommand as MigrateRefreshCommand;
use Cms\Framework\Database\Console\Migrations\RollbackCommand as MigrateRollbackCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migrate' => 'cms.command.migrate',
        'MigrateInstall' => 'cms.command.migrate.install',
        'MigrateRefresh' => 'cms.command.migrate.refresh',
        'MigrateReset' => 'cms.command.migrate.reset',
        'MigrateRollback' => 'cms.command.migrate.rollback',
        'MigrateStatus' => 'cms.command.migrate.status',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'MigrateMake' => 'cms.command.migrate.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands(array_merge(
            $this->commands, $this->devCommands
        ));
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('cms.command.migrate', function ($app) {
            return new MigrateCommand($app['cms.migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateInstallCommand()
    {
        $this->app->singleton('cms.command.migrate.install', function ($app) {
            return new MigrateInstallCommand($app['cms.migration.repository']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('cms.command.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['cms.migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton('cms.command.migrate.refresh', function () {
            return new MigrateRefreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton('cms.command.migrate.reset', function ($app) {
            return new MigrateResetCommand($app['cms.migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('cms.command.migrate.rollback', function ($app) {
            return new MigrateRollbackCommand($app['cms.migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton('cms.command.migrate.status', function ($app) {
            return new MigrateStatusCommand($app['cms.migrator']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(array_values($this->commands), array_values($this->devCommands));
    }
}