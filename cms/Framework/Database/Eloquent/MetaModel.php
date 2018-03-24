<?php

namespace Cms\Framework\Database\Eloquent;

use Illuminate\Support\Str;

/**
 * Class MetaModel
 * @package Cms\Framework\Database\Eloquent
 *
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
abstract class MetaModel extends Model
{

    /**
     * The model's attributes.
     *
     * @var array
     */
    public $attributes = [
        'value' => null
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Disallow null or empty values
        static::creating(function (MetaModel $model) {
            if (is_null($model->value) || $model->value === '') {
                return false;
            }
        });

        static::updating(function (MetaModel $model) {
            if (is_null($model->value) || $model->value === '') {
                $model->delete();

                return false;
            }
        });
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

        return $this->getTablePrefix().str_replace(
            '\\', '', Str::snake(class_basename($this))
        );
    }
}