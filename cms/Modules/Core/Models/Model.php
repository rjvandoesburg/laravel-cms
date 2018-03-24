<?php

namespace Cms\Modules\Core\Models;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Get the prefix used for tables
     *
     * @return string
     */
    public function getTableprefix()
    {
        return app('config')->get('cms.database.prefix');
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        // If the user has set a table, use that one instead
        if (! isset($this->table)) {
            return $this->table;
        }

        return $this->getTableprefix().parent::getTable();
    }
}