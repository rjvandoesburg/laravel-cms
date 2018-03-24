<?php

namespace Cms\Framework\Database\Eloquent;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Get the prefix used for tables
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return app('config')->get('cms.database.prefix');
    }

    /**
     * Get the table associated with the model.
     *
     * @param bool $prefix Set to false if table basename should be returned.
     *
     * @return string
     */
    public function getTable($prefix = true)
    {
        // If the user has set a table, use that one instead
        if (isset($this->table)) {
            return $this->table;
        }

        $table = parent::getTable();
        if ($prefix) {
            return $this->getTablePrefix().$table;
        }

        return $table;
    }
}