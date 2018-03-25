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

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function getFillable()
    {
        $class = static::class;

        // Loop through all traits to get additional fillables
        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = 'getFillableFor'.class_basename($trait))) {
                $fillable = forward_static_call([$class, $method]);
                $this->fillable = array_merge($this->fillable, $fillable);
            }
        }

        return parent::getFillable();
    }
}