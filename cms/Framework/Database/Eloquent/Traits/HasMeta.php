<?php

namespace Cms\Framework\Database\Eloquent\Traits;

use Cms\Framework\Database\Eloquent\Model;

/**
 * Trait HasMeta
 * @package Cms\Framework\Database\Eloquent\Traits
 *
 * @property \Illuminate\Database\Eloquent\Collection|\Cms\Framework\Database\Eloquent\MetaModel[] $meta
 */
trait HasMeta
{
    protected $_meta = [];

    /**
     * Boot the trait
     */
    public static function bootHasMeta()
    {
        static::saved(function (Model $model) {
            if (! empty($model->_meta)) {

                $metaModel = [];
                foreach ($model->_meta as $key => $value) {
                    /** @var \Cms\Framework\Database\Eloquent\Model $class */
                    $class = $model->getMetaImplementationClass();
                    $metaModel[] = $class::updateOrCreate([
                        $model->getForeignKey() => $model->getKey(),
                        'key'   => $key,
                    ], [
                        'value' => $value
                    ]);
                }

                $model->meta()->saveMany($metaModel);
            }
        });
    }

    /**
     * Get the fillable fields for the trait
     *
     * @return array
     */
    public static function getFillableForHasMeta()
    {
        return [
            'meta'
        ];
    }

    /**
     * Grab the meta value
     *
     * @param array $value
     */
    public function setMetaAttribute($value)
    {
        $this->_meta = $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany($this->getMetaImplementationClass(), $this->getRelationKey());
    }

    /**
     * @return string
     */
    abstract public function getMetaClass();

    /**
     * We try to resolve the meta class because it might be a base class (which in turn is bound in de IoC container
     *
     * @return string
     */
    protected function getMetaImplementationClass()
    {
        return resolve($this->getMetaClass());
    }

    /**
     * @return string
     */
    public function getRelationKey()
    {
        return str_singular($this->getTable(false)) . '_id';
    }

    /**
     * @param $query
     * @param $key
     * @param null $operator
     * @param null $value
     * @param string $boolean
     */
    public function scopeMeta($query, $key, $operator = null, $value = null, $boolean = 'and')
    {
        $query->whereHas('meta', function ($query) use ($key, $operator, $value, $boolean) {
            return $query->where('key', $key)
                ->where('value', $operator, $value, $boolean);
        });
    }
}