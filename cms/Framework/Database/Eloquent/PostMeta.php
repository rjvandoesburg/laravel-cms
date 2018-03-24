<?php

namespace Cms\Framework\Database\Eloquent;

class PostMeta extends MetaModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'key',
        'value'
    ];
}