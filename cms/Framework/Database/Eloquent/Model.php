<?php

namespace Cms\Framework\Database\Eloquent;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Set to false if the table name should not be prefixed.
     *
     * @var bool
     */
    protected $prefixed = true;

    /**
     * Get the prefix used for tables.
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
        $table = parent::getTable();
        if ($prefix && $this->prefixed) {
            return $this->getTablePrefix().$table;
        }

        return $table;
    }

    /**
     * Get a table with the set prefix.
     *
     * @param $table
     *
     * @return string
     */
    public static function getPrefixed($table)
    {
        return app('config')->get('cms.database.prefix').$table;
    }
}