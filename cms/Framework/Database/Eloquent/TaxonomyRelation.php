<?php

namespace Cms\Framework\Database\Eloquent;


class TaxonomyRelation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['term_taxonomy_id'];

}