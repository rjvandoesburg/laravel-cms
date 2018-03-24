<?php

namespace Cms\Modules\Core\Models;

use Cms\Framework\Database\Eloquent\MetaModel;

class UserMeta extends MetaModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'key',
        'value'
    ];
}