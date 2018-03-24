<?php

namespace Cms\Framework\Database\Eloquent;

use Cms\Framework\Database\Eloquent\Traits\HasMeta;
use Cms\Modules\Core\Models\Post;
use Cms\Modules\Core\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class PostModel extends Model
{
    use HasMeta;
    use SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'publish';
    const STATUS_TRASHED = 'trash';
    const STATUS_PRIVATE = 'private';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['meta'];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author_id',
        'content',
        'type',
        'status',
        'creator_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'published_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::saving(function (Post $post) {
            if ($post->author_id === null && auth()->check()) {
                $post->author_id = auth()->id;
            }

            if ($post->creator_id === null && auth()->check()) {
                $post->creator_id = auth()->id;
            }

            if (! $post->exists && ! $post->isDirty('created_at')) {
                $time = $post->freshTimestamp();
                $post->published_at = $time;
            }
        });

        parent::boot();
    }

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['type'])) {
            $attributes['type'] = static::class;
        }

        parent::__construct($attributes);
    }

    /**
     * @return string
     */
    public function getMetaClass()
    {
        return PostMeta::class;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Cms\Modules\Core\Models\User
     */
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Cms\Modules\Core\Models\User
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus($query, $status)
    {
        if ($status === self::STATUS_TRASHED) {
            return $query->onlyTrashed();
        }

        return $query->where('status', $status);
    }

    public function isStatus($status)
    {
        return $this->status === $status;
    }

    public function isDraft()
    {
        return $this->isStatus(self::STATUS_DRAFT);
    }

    public static function statuses()
    {
        return (new static)->query()
            ->select(['status', \DB::raw('COUNT(*) as count')])
            ->groupBy('status')
            ->get()
            ->map(function ($status) {
                return (object)[
                    'value' => $status->status,
                    'name'  => ucfirst($status->status),
                    'count' => $status->count
                ];
            });
    }

    /**
     * check if a post has been published
     *
     * @return bool
     */
    public function published()
    {
        // Have to use timestamp because gte not working in tests (too fast?)
        return $this->isStatus(static::STATUS_PUBLISHED)
            && ! is_null($this->published_at) && $this->published_at->timestamp >= \Carbon\Carbon::now()->timestamp;
    }

    /**
     * Publish a post
     */
    public function publish()
    {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

        $this->published_at = $time = $this->freshTimestamp();
        $this->status = static::STATUS_PUBLISHED;

        $query->update([
            'published_at' => $this->fromDateTime($time),
            'status'       => static::STATUS_PUBLISHED
        ]);
    }
}