<?php

namespace Cms\Framework\Database\Console\Migrations;

use Illuminate\Support\Str;
use Illuminate\Support\Composer;
use Cms\Framework\Database\Migrations\MigrationCreator;

class MigrateMakeCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:make:migration {module : The name of the module} {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new cms migration file for a given module';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Cms\Framework\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $module = $this->input->getArgument('module');

        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;

        // If no table was given as an option but a create option is given then we
        // will use the "create" option as the table name. This allows the devs
        // to pass a table name into this option as a short-cut for creating.
        if (! $table && is_string($create)) {
            $table = $create;

            $create = true;
        }

        // Next, we will attempt to guess the table name if this the migration has
        // "create" in the name. This will allow us to provide a convenient way
        // of creating migrations that create new tables for the application.
        if (! $table) {
            if (preg_match('/^create_(\w+)_table$/', $name, $matches)) {
                $table = $matches[1];

                $create = true;
            }
        }

        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $this->writeMigration($module, $name, $table, $create);

        $this->composer->dumpAutoloads();
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $module
     * @param string $name
     * @param string $table
     * @param bool $create
     *
     * @throws \Exception
     */
    protected function writeMigration($module, $name, $table, $create)
    {
        $file = pathinfo($this->creator->create(
            $name, $this->getMigrationPath($module), $table, $create
        ), PATHINFO_FILENAME);

        $this->line("<info>Created cms Migration for {$module}:</info> {$file}");
    }
}
