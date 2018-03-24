<?php

namespace Cms\Framework\Database\Eloquent\Traits;

use Cms\Framework\Database\Eloquent\Comment;

trait IsCommentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'object');
    }

    /**
     * @return string
     */
    abstract public function getCommentClass();

}