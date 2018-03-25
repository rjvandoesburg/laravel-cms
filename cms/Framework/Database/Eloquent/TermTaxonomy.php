<?php

namespace Cms\Framework\Database\Eloquent;

/**
 * Class TermTaxonomy
 * @package Cms\Framework\Database\Eloquent
 *
 * @property int $id
 * @property int $term_id
 * @property string $taxonomy
 * @property string $description
 * @property int $parent
 * @property int $count
 */
class TermTaxonomy extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'term_taxonomy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term_id',
        'term_type',
        'description'
    ];

    public function related()
    {
        $taxonomyRelationshipsTable = static::getPrefixed('taxonomy_relationships');

        $query = $this->newQuery()
            ->addSelect($taxonomyRelationshipsTable.'.*')
            ->join($taxonomyRelationshipsTable, function ($join) use ($taxonomyRelationshipsTable) {
                $termTaxonomy = static::getPrefixed('term_taxonomy');
                $join->on($taxonomyRelationshipsTable.'.term_taxonomy_id', $termTaxonomy.'.id')
                    ->where('term_taxonomy_id', $this->id);
            });

        return $query;
    }

    /**
     * Get all linked categories
     *
     * @return mixed
     */
    public function getRelatedAttribute()
    {
        $taxonomyRelationshipsTable = static::getPrefixed('taxonomy_relationships');
        // Get the relation types
        $query = $this->newQuery()
            ->select('object_type')
            ->join($taxonomyRelationshipsTable, function ($join) use ($taxonomyRelationshipsTable) {
                $termTaxonomy = static::getPrefixed('term_taxonomy');
                $join->on($taxonomyRelationshipsTable.'.term_taxonomy_id', $termTaxonomy.'.id')
                    ->where('term_taxonomy_id', $this->id);
            })
            ->groupBy('object_type');

        $relations = collect();
        foreach ($query->pluck('object_type') as $relation) {
            $relations = $relations->merge($this->morphRelation($relation)->get());
        }

        return $relations;
    }

    public function morphRelation($related)
    {
        $taxonomyRelationshipsTable = static::getPrefixed('taxonomy_relationships');

        return $this->morphedByMany($related, 'object', $taxonomyRelationshipsTable, 'term_taxonomy_id', 'object_id');
    }

}