<?php

namespace Cms\Framework\Database\Eloquent;

use Cms\Framework\Database\Eloquent\Traits\HasMeta;

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
     * @var bool|\Cms\Framework\Database\Eloquent\TermTaxonomy
     */
    protected $_termTaxonomy = false;

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
            $term->_termTaxonomy->term_id = $term->id;
            $term->_termTaxonomy->save();
        });

        static::creating(function (Term $term) {
            if (is_null($term->getAttribute('slug'))) {
                $term->setAttribute('slug', str_slug($term->getAttribute('name')));
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|TermTaxonomy
     */
    public function taxonomy()
    {
        return $this->morphOne(TermTaxonomy::class, 'term');
    }

    /**
     * Create or get the taxonomy for the term
     *
     * @param Term $term
     */
    public static function createOrUpdateTaxonomy(Term $term)
    {
        if (! $term->_termTaxonomy) {
            $term->_termTaxonomy = TermTaxonomy::firstOrCreate([
                'term_id' => $term->id,
                'term_type' => static::class
            ]);
        }
    }

    /**
     * Create or get the taxonomy for the term
     */
    public function createTaxonomy()
    {
        TermTaxonomy::firstOrCreate([
            'term_id' => $this->id,
            'term_type' => static::class
        ]);
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

    protected function setSlugAttribute($value)
    {
        // TODO: Implement functionality

        // check if slug already exists
        $this->attributes['slug'] = $value;
    }

    /**
     * @param $description
     */
    public function setDescriptionAttribute($description)
    {
        if (is_null($this->_termTaxonomy = $this->taxonomy) || $this->taxonomy === false) {
            $this->_termTaxonomy = new TermTaxonomy([
                'term_type' => static::class
            ]);
        }

        $this->_termTaxonomy->description = $description;
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->taxonomy->description;
    }
}