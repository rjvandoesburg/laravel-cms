<?php

namespace Cms\Framework\Database\Eloquent;

use Cms\Framework\Database\Eloquent\Traits\HasMeta;

abstract class Comment extends Model
{
    use HasMeta;

    /**
     * @var array
     */
    protected $fillable = [
        'content',
        'user_id',
        'parent_id'
    ];

    /**
     * Get the commented object
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo('object');
    }

    /**
     * @return string
     */
    public function getMetaClass()
    {
        return CommentMeta::class;
    }
}