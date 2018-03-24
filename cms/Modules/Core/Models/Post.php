<?php

namespace Cms\Modules\Core\Models;

use Cms\Framework\Database\Eloquent\PostModel;
use Cms\Framework\Database\Eloquent\Traits\HasCategories;
use Cms\Framework\Database\Eloquent\Traits\IsCommentable;

class Post extends PostModel
{
    use IsCommentable;
    use HasCategories;

    /**
     * @return string
     */
    public function getCommentClass()
    {
        return PostComment::class;
    }
}