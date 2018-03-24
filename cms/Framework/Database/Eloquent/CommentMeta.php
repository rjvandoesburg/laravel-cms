<?php

namespace Cms\Framework\Database\Eloquent;

class CommentMeta extends MetaModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'key',
        'value'
    ];

}