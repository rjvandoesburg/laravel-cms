<?php

namespace Cms\Framework\Database\Eloquent;

class TermMeta extends MetaModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term_id',
        'key',
        'value'
    ];
}