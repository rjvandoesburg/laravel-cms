<?php

namespace Cms\Framework\Database\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class BaseCommand extends Command
{

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        $paths = [];
        $finder = (new Finder)->in($this->laravel->basePath().'/cms/Modules')->directories()->depth('== 0');
        /** @var \splFileInfo $module */
        foreach ($finder as $module) {
            // check if the path exists
            if (($path = realpath($module->getPathname().'/Database/Migrations')) !== false) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Get the path to the migration directory.
     *
     * @param string $module Name of the module
     *
     * @return string
     */
    protected function getMigrationPath($module)
    {
        $path = $this->laravel->basePath().'/cms/Modules/'.Str::studly($module).'/Database/Migrations';

        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
