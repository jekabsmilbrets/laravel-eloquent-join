<?php

namespace Fico7489\Laravel\SortJoin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait SortJoinTrait
{
    private $selected = false;
    private $joinedTables = [];
    private $relationClauses = [];

    public function scopeWhereJoin(Builder $builder, $column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->relationClauses[] = ['column' => $column, 'operator' => $operator, 'value' => $value, 'boolean' => $boolean];
        $column = $this->performJoin($builder, $column);

        return $builder->where($column, $operator, $value, $boolean);
    }

    public function scopeOrWhereJoin(Builder $builder, $column, $operator = null, $value)
    {
        $this->relationClauses[] = ['column' => $column, 'operator' => $operator, 'value' => $value, 'boolean' => 'and'];
        $column = $this->performJoin($builder, $column);

        return $builder->orWhere($column, $operator, $value);
    }

    public function scopeOrderByJoin(Builder $builder, $column, $sortBy = 'asc')
    {
        $column = $this->performJoin($builder, $column);
        return $builder->orderBy($column, $sortBy);
    }

    private function performJoin($builder, $relations){
        $relations = explode('.', $relations);

        $column = end($relations);
        $baseTable = $this->getTable();
        $baseModel = $this;

        $currentTable = $this->getTable();
        $currentModel = $this;

        foreach($relations as $relation){
            if($relation == $column){
                //last item in $relations argument is sort|where column
                continue;
            }

            $relatedRelation = $currentModel->$relation();
            $relatedModel = $relatedRelation->getRelated();
            //print_r($relatedModel->relationClauses);
            $relatedPrimaryKey = $relatedModel->primaryKey;
            $relatedTable = $relatedModel->getTable();

            if(array_key_exists($relation, $this->joinedTables)){
                $relatedTableAlias = $this->joinedTables[$relation];
            }else{
                $relatedTableAlias = uniqid();

                $keyRelated = $relatedRelation->getForeignKey();
                if($relatedRelation instanceof BelongsTo){
                    $builder->leftJoin($relatedTable . ' as ' . $relatedTableAlias, $relatedTableAlias . '.' . $relatedPrimaryKey, '=', $currentTable . '.' . $keyRelated);
                }elseif($relatedRelation instanceof HasOne){
                    $keyRelated = last(explode('.', $keyRelated));
                    $builder->leftJoin($relatedTable . ' as ' . $relatedTableAlias, $relatedTableAlias . '.' . $keyRelated, '=', $currentTable . '.' . $relatedPrimaryKey);
                }

                $columnsWhere = collect($relatedModel->relationClauses)->pluck('column')->toArray();

                //by default apply where deleted_at is null if model is using soft deletes, if any where clause have deleted_at columnn do not apply
                if(method_exists($relatedModel, 'getQualifiedDeletedAtColumn') &&  ! in_array('deleted_at', $columnsWhere)){
                    $builder->where([$relatedTableAlias . '.deleted_at' => null]);
                }
            }

            $currentTable = $relatedTableAlias;
            $currentModel = $relatedModel;

            $this->joinedTables[$relation] = $relatedTableAlias;
        }

        if( ! $this->selected){
            $this->selected = true;
            $builder->select ($baseTable . '.*')->groupBy ($baseTable . '.' . $baseModel->primaryKey);
        }

        return $currentTable . '.' . $column;
    }
}
