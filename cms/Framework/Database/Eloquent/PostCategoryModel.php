<?php

namespace Cms\Framework\Database\Eloquent;

abstract class PostCategoryModel extends Term
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meta_field'
    ];
}