<?php

namespace Cms\Framework\Database\Migrations;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class Migrator extends \Illuminate\Database\Migrations\Migrator
{

    /**
     * Create a new migrator instance.
     *
     * @param \Cms\Framework\Database\Migrations\MigrationRepositoryInterface $repository
     * @param \Illuminate\Database\ConnectionResolverInterface $resolver
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(MigrationRepositoryInterface $repository,
        Resolver $resolver,
        Filesystem $files)
    {
        $this->files = $files;
        $this->resolver = $resolver;
        $this->repository = $repository;

        parent::__construct($repository, $resolver, $files);
    }

}
