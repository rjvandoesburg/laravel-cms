<?php

namespace Cms\Modules\Core\Models;

use Cms\Framework\Database\Eloquent\Traits\HasMeta;
use Illuminate\Notifications\Notifiable;
use Cms\Framework\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasMeta;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'meta'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return string
     */
    public function getMetaClass()
    {
        return UserMeta::class;
    }
}