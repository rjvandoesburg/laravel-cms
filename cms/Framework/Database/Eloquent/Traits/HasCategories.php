<?php

namespace Cms\Framework\Database\Eloquent\Traits;

use Cms\Framework\Database\Eloquent\Model;
use Cms\Framework\Database\Eloquent\TermTaxonomy;
use Illuminate\Support\Arr;

trait HasCategories
{

    /**
     * @var array|bool
     */
    protected $_categories = false;

    /**
     * Boot the trait
     */
    public static function bootHasCategories()
    {
        static::saved(function (Model $model) {
            // When it is not set to false we know it was set by the user
            if ($model->_selectedCategories !== false) {
                $model->categories()->sync($model->_categories);
            }
        });
    }

    /**
     * Get the fillable fields for the trait
     *
     * @return array
     */
    public static function getFillableForHasCategories()
    {
        return [
            'categories'
        ];
    }

    /**
     * Get all linked categories
     */
    public function categories()
    {
        return $this->morphToMany(TermTaxonomy::class, 'object', static::getPrefixed('taxonomy_relationships'))
            ->withTimestamps();
    }

    /**
     * Handle the categories attribute
     *
     * @param mixed $value
     */
    public function setCategoriesAttribute($value)
    {
        $this->_categories = Arr::wrap($value);
    }

}