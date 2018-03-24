<?php

namespace Cms\Core\Tests;

use Cms\Framework\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends \Tests\TestCase
{
    use DatabaseMigrations;
}