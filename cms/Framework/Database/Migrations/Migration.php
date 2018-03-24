<?php

namespace Cms\Framework\Database\Migrations;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{

    /**
     * Get the prefix for the cms tables
     *
     * @return string
     */
    protected function getPrefix()
    {
        return app('config')->get('cms.database.prefix', '');
    }
}
