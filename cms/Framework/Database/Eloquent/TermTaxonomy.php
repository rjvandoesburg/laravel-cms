<?php

namespace Cms\Framework\Database\Eloquent;

class TermTaxonomy extends Model
{

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
        $query = $this->newQuery()->addSelect('taxonomy_relationships.*')->join('taxonomy_relationships', function ($join) {
            $join->on('taxonomy_relationships.term_taxonomy_id', 'term_taxonomy.id')
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
        // Get the relation types
        $query = $this->newQuery()->select('object_type')->join('taxonomy_relationships', function ($join) {
            $join->on('taxonomy_relationships.term_taxonomy_id', 'term_taxonomy.id')
                ->where('term_taxonomy_id', $this->id);
        })->groupBy('object_type');

        $relations = collect();
        foreach ($query->pluck('object_type') as $relation) {
            $relations = $relations->merge($this->morphRelation($relation)->get());
        }

        return $relations;
    }

    public function morphRelation($related)
    {
        return $this->morphedByMany($related, 'object', 'taxonomy_relationships', 'term_taxonomy_id', 'object_id');
    }

}