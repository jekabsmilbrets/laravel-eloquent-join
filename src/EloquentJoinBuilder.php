<?php

namespace Fico7489\Laravel\EloquentJoin;

use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidAggregateMethod;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelation;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationClause;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationGlobalScope;
use Fico7489\Laravel\EloquentJoin\Exceptions\InvalidRelationWhere;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\JoinClause;

class EloquentJoinBuilder extends Builder
{
    //constants
    const AGGREGATE_SUM      = 'SUM';
    const AGGREGATE_AVG      = 'AVG';
    const AGGREGATE_MAX      = 'MAX';
    const AGGREGATE_MIN      = 'MIN';
    const AGGREGATE_COUNT    = 'COUNT';

    //use table alias for join (real table name or uniqid())
    private $useTableAlias = false;

    //appendRelationsCount
    private $appendRelationsCount = false;

    //leftJoin
    private $leftJoin = true;

    //aggregate method
    private $aggregateMethod = self::AGGREGATE_MAX;

    //base builder
    public $baseBuilder;

    //store if ->select(...) is already called on builder (we want only one groupBy())
    private $selected = false;

    //store joined tables, we want join table only once (e.g. when you call orderByJoin more time)
    private $joinedTables = [];

    //store clauses on relation for join
    public $relationClauses = [];

    //query methods
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column instanceof \Closure) {
            $query = $this->model->newModelQuery();
            $baseBuilderCurrent = $this->baseBuilder ? $this->baseBuilder : $this;
            $query->baseBuilder = $baseBuilderCurrent;

            $column($query);

            $this->query->addNestedWhereQuery($query->getQuery(), $boolean);
        } else {
            $this->query->where(...func_get_args());
        }

        return $this;
    }

    public function whereJoin($column, $operator, $value, $boolean = 'and')
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->where($column, $operator, $value, $boolean);
    }

    public function orWhereJoin($column, $operator, $value)
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->orWhere($column, $operator, $value);
    }

    public function whereInJoin($column, $values, $boolean = 'and', $not = false)
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->whereIn($column, $values, $boolean, $not);
    }

    public function whereNotInJoin($column, $values, $boolean = 'and')
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->whereNotIn($column, $values, $boolean);
    }

    public function orWhereInJoin($column, $values)
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->orWhereIn($column, $values);
    }

    public function orWhereNotInJoin($column, $values)
    {
        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);

        return $this->orWhereNotIn($column, $values);
    }

    public function orderByJoin($column, $direction = 'asc', $aggregateMethod = null)
    {
        $dotPos = strrpos($column, '.');

        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($column);
        if (false !== $dotPos) {
            //order by related table field
            $aggregateMethod = $aggregateMethod ? $aggregateMethod : $this->aggregateMethod;
            $this->checkAggregateMethod($aggregateMethod);

            $sortsCount = count($this->query->orders ?? []);
            $sortAlias = 'sort'.(0 == $sortsCount ? '' : ($sortsCount + 1));

            $query->selectRaw($aggregateMethod.'('.$column.') as '.$sortAlias);

            return $this->orderByRaw($sortAlias.' '.$direction);
        }

        //order by base table field

        return $this->orderBy($column, $direction);
    }

    public function joinRelations($relations, $leftJoin = null)
    {
        $leftJoin = null !== $leftJoin ? $leftJoin : $this->leftJoin;

        $query = $this->baseBuilder ? $this->baseBuilder : $this;
        $column = $query->performJoin($relations.'.FAKE_FIELD', $leftJoin);

        return $this;
    }

    //helpers methods
    private function performJoin($relations, $leftJoin = null)
    {
        //detect join method
        $leftJoin   = null !== $leftJoin ? $leftJoin : $this->leftJoin;
        $joinMethod = $leftJoin ? 'leftJoin' : 'join';

        //detect current model data
        $relations = explode('.', $relations);
        $column    = end($relations);
        $baseModel = $this->getModel();
        $baseTable = $baseModel->getTable();
        $basePrimaryKey = $baseModel->getKeyName();

        $currentModel      = $baseModel;
        $currentTableAlias = $baseTable;

        $relationsAccumulated = [];
        foreach ($relations as $relation) {
            if ($relation == $column) {
                //last item in $relations argument is sort|where column
                break;
            }

            /** @var Relation $relatedRelation */
            $relatedRelation   = $currentModel->$relation();
            $relatedModel      = $relatedRelation->getRelated();
            $relatedPrimaryKey = $relatedModel->getKeyName();
            $relatedTable      = $relatedModel->getTable();
            $relatedTableAlias = $this->useTableAlias ? uniqid() : $relatedTable;

            $relationsAccumulated[]    = $relatedTableAlias;
            $relationAccumulatedString = implode('_', $relationsAccumulated);

            //relations count
            if ($this->appendRelationsCount) {
                $this->selectRaw('COUNT('.$relatedTableAlias.'.'.$relatedPrimaryKey.') as '.$relationAccumulatedString.'_count');
            }

            if (!in_array($relationAccumulatedString, $this->joinedTables)) {
                $joinQuery = $relatedTable.($this->useTableAlias ? ' as '.$relatedTableAlias : '');
                if ($relatedRelation instanceof BelongsTo) {
                    $relatedKey = ((float) \App::version() < 5.8) ? $relatedRelation->getQualifiedForeignKey() : $relatedRelation->getQualifiedForeignKeyName();
                    $relatedKey = last(explode('.', $relatedKey));
                    $ownerKey = ((float) \App::version() < 5.8) ? $relatedRelation->getOwnerKey() : $relatedRelation->getOwnerKeyName();

                    $this->$joinMethod($joinQuery, function ($join) use ($relatedRelation, $relatedTableAlias, $relatedKey, $currentTableAlias, $ownerKey) {
                        $join->on($relatedTableAlias.'.'.$ownerKey, '=', $currentTableAlias.'.'.$relatedKey);

                        $this->joinQuery($join, $relatedRelation, $relatedTableAlias);
                    });
                } elseif (
                    $relatedRelation instanceof HasOne || 
                    $relatedRelation instanceof HasMany || 
                    $relatedRelation instanceof MorphOne || 
                    $relatedRelation instanceof MorphMany
                ) {
                    $relatedKey = $relatedRelation->getQualifiedForeignKeyName();
                    $relatedKey = last(explode('.', $relatedKey));
                    $localKey = $relatedRelation->getQualifiedParentKeyName();
                    $localKey = last(explode('.', $localKey));

                    $this->$joinMethod($joinQuery, function ($join) use ($relatedRelation, $relatedTableAlias, $relatedKey, $currentTableAlias, $localKey) {
                        $join->on($relatedTableAlias.'.'.$relatedKey, '=', $currentTableAlias.'.'.$localKey);

                        $this->joinQuery($join, $relatedRelation, $relatedTableAlias);
                    });
                } else {
                    throw new InvalidRelation();
                }
            }

            $currentModel      = $relatedModel;
            $currentTableAlias = $relatedTableAlias;

            $this->joinedTables[] = implode('_', $relationsAccumulated);
        }

        if (!$this->selected && count($relations) > 1) {
            $this->selected = true;
            $this->selectRaw($baseTable.'.*');
            $this->groupBy($baseTable.'.'.$basePrimaryKey);
        }

        return $currentTableAlias.'.'.$column;
    }

    private function joinQuery($join, $relation, $relatedTableAlias)
    {
        /** @var Builder $relationQuery */
        $relationBuilder = $relation->getQuery();   

        $wheres = array_slice($relationBuilder->getQuery()->wheres, $relation instanceof BelongsTo ? 1 : 2);

        foreach ($wheres as $clause) {

            $method = $clause['type'] === 'Basic' ? 'where' : 'where'.$clause['type'];
            unset ($clause['type']);

            if (!isset($clause['column'])) {
                throw new InvalidRelationWhere();
            }

            // Remove first alias table name
            $partsColumn = explode(".", $clause['column']);

            if (count($partsColumn) > 1) {
                $clause['column'] = implode(".", array_slice($partsColumn, 1));
            }

            $this->applyWheresOnRelation($join, $method, array_values($clause), $relatedTableAlias);
        }

        //apply global SoftDeletingScope
        foreach ($relationBuilder->scopes as $scope) {
            if ($scope instanceof SoftDeletingScope) {
                $this->applyScopeOnRelation($join, 'withoutTrashed', [], $relatedTableAlias);
            } else {
                throw new InvalidRelationGlobalScope();
            }
        }
    }

    private function applyWheresOnRelation(JoinClause $join, string $method, array $params, string $relatedTableAlias)
    {        
        try {
            if (is_array($params[0])) {
                foreach ($params[0] as $k => $param) {
                    $params[0][$relatedTableAlias.'.'.$k] = $param;
                    unset($params[0][$k]);
                }
            } else {
                $params[0] = $relatedTableAlias.'.'.$params[0];
            }

            call_user_func_array([$join, $method], $params);
        } catch (\Exception $e) {
            throw new InvalidRelationWhere();
        }
    }

    private function applyScopeOnRelation(JoinClause $join, string $method, array $params, string $relatedTableAlias)
    {
        if (in_array($method, ['withoutTrashed', 'onlyTrashed', 'withTrashed'])) {
            if ('withTrashed' == $method) {
                //do nothing
            } elseif ('withoutTrashed' == $method) {
                call_user_func_array([$join, 'where'], [$relatedTableAlias.'.deleted_at', '=', null]);
            } elseif ('onlyTrashed' == $method) {
                call_user_func_array([$join, 'where'], [$relatedTableAlias.'.deleted_at', '<>', null]);
            }
        } else {
            throw new InvalidRelationClause();
        }
    }

    private function checkAggregateMethod($aggregateMethod)
    {
        if (!in_array($aggregateMethod, [
            self::AGGREGATE_SUM,
            self::AGGREGATE_AVG,
            self::AGGREGATE_MAX,
            self::AGGREGATE_MIN,
            self::AGGREGATE_COUNT,
        ])) {
            throw new InvalidAggregateMethod();
        }
    }

    //getters and setters
    public function isUseTableAlias(): bool
    {
        return $this->useTableAlias;
    }

    public function setUseTableAlias(bool $useTableAlias)
    {
        $this->useTableAlias = $useTableAlias;

        return $this;
    }

    public function isLeftJoin(): bool
    {
        return $this->leftJoin;
    }

    public function setLeftJoin(bool $leftJoin)
    {
        $this->leftJoin = $leftJoin;

        return $this;
    }

    public function isAppendRelationsCount(): bool
    {
        return $this->appendRelationsCount;
    }

    public function setAppendRelationsCount(bool $appendRelationsCount)
    {
        $this->appendRelationsCount = $appendRelationsCount;

        return $this;
    }

    public function getAggregateMethod(): string
    {
        return $this->aggregateMethod;
    }

    public function setAggregateMethod(string $aggregateMethod)
    {
        $this->checkAggregateMethod($aggregateMethod);
        $this->aggregateMethod = $aggregateMethod;

        return $this;
    }
}
