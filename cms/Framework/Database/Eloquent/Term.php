<?php

namespace Cms\Framework\Database\Eloquent;

use Cms\Framework\Database\Eloquent\Traits\HasMeta;
use Illuminate\Support\Arr;

/**
 * Class Term
 * @package Cms\Framework\Database\Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Cms\Framework\Database\Eloquent\TermTaxonomy $taxonomy
 */
abstract class Term extends Model
{
    use HasMeta;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'terms';

    /**
     * @var array
     */
    protected $_taxonomyData = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /**
     * @var array
     */
    protected $with = ['taxonomy'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function (Term $term) {
            $term->taxonomy()->save(
                new TermTaxonomy([
                    'description' => Arr::get($term->_taxonomyData, 'description')
                ])
            );
        });

        static::creating(function (Term $term) {
            if (is_null($term->getAttribute('slug'))) {
                $term->setAttribute('slug', str_slug($term->getAttribute('name')));
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|\Cms\Framework\Database\Eloquent\TermTaxonomy
     */
    public function taxonomy()
    {
        return $this->morphOne(TermTaxonomy::class, 'term');
    }

    /**
     * @return int
     */
    public function getTaxonomyIdAttribute()
    {
        return $this->taxonomy->id;
    }

    /**
     * @return string
     */
    public function getMetaClass()
    {
        return TermMeta::class;
    }

    /**
     * @return int
     */
    public function getCountAttribute()
    {
        return $this->taxonomy->related()->count();
    }

    /**
     * @param $value
     */
    protected function setSlugAttribute($value)
    {
        // TODO: Implement functionality

        // check if slug already exists
        $this->attributes['slug'] = $value;
    }

    /**
     * @param $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->_taxonomyData['description'] = $value;
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->taxonomy->description;
    }
}