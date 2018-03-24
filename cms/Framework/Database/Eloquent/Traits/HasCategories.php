<?php

namespace Cms\Framework\Database\Eloquent\Traits;

use Cms\Framework\Database\Eloquent\TermTaxonomy;

trait HasCategories
{

    /**
     * @var array
     */
    protected $selectedCategories = [];

    /**
     * Boot the trait
     */
    public static function bootHasCategories()
    {

    }

    /**
     * Get all linked categories
     */
    public function categories()
    {
        return $this->morphToMany(TermTaxonomy::class, 'object', 'taxonomy_relationships')->withTimestamps();
    }

    /**
     * Handle the categories attribute
     *
     * @param $categories
     */
    public function setCategoriesAttribute($categories)
    {
        $this->selectedCategories = $categories;
    }

}