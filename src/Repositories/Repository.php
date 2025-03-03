<?php

namespace Soukar\Larepo\Repositories;

use Soukar\Larepo\Interfaces\DataObjectTransfer;
use Soukar\Larepo\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Soukar\Larepo\Services\DataLimitService;
use Soukar\Larepo\Services\RepositoryQueryService;

class Repository implements RepositoryInterface
{

    public function __construct(
        private Model $model,
    )
    {
    }

    public function getWhere(array $whereFilters = [], array $whereInFilters = [], DataLimitService $dataLimit = new DataLimitService(), array $relations = [], array $whereHasRelations = [], array $whereDoesNotHaveRelations = [], array $orderBy = [], array $joins = [], array $select =[] , array $scopes=[]): mixed
    {
        $query = $this->getWhereQuery(
            $whereFilters,
            $whereInFilters,
            $dataLimit,
            $relations,
            $whereHasRelations,
            $whereDoesNotHaveRelations,
            $orderBy,
            $joins,
            $select
        );

        return $dataLimit->get($query);
    }

    public function getWhereQuery(array $whereFilters = [], array $whereInFilters = [], DataLimitService $dataLimit = new DataLimitService(), array $relations = [], array $whereHasRelations = [], array $whereDoesNotHaveRelations = [], array $orderBy = [], array $joins = [], array $select =[] ,  array $scopes=[]): Builder|\Illuminate\Database\Eloquent\Builder
    {

        $query = ($this->model)::query();
        $repositoryQueryService = new RepositoryQueryService();
        $repositoryQueryService->whereQuery($query,$whereFilters);
        if(count($relations)){
            $query->with($relations);
        }


        if(count($whereHasRelations))
        {

            foreach ($whereHasRelations as $key => $value)
            {
                if(is_numeric($key))
                    $query->whereHas($value);
                else
                  $query->whereHas($key , $value);
            }
        }

        if(count($whereInFilters)){
            foreach ($whereInFilters as $column=>$search)
            {
                $query->whereIn($column,$search);
            }
        }

        if(count($joins))
        {
            foreach ($joins as $tableToJoin)
            {
                $query->join(...$tableToJoin);
            }
        }

        if(count($select)){
            $query->select($select);
        }

        if(count($scopes))
        {
            foreach ($scopes as $scope)
            {
                $query->$scope();
            }
        }

        foreach ($orderBy as $column)
        {
            if(is_array($column)){
                $query->orderBy(...$column);
            }else{
                $query->orderBy($column);
            }
        }

        return $query;
    }

    public function findById($id, array $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

    public function findWhere(array $whereFilters, array $relations = [],)
    {
        // TODO: Implement findWhere() method.
    }

    public function add(array|DataObjectTransfer $data): Model
    {
       return $this->model::create($data);
    }

    public function update(array|DataObjectTransfer $data, $id): bool
    {
        if($id instanceof Model)
            return $id->update($data);

        return $this->model::whereId($id)->update($data);
    }

    public function delete(Model|string $id, array $relationsToDelete = []): bool
    {
        if(is_string($id))
            $model = $this->findById($id);
        else
            $model=$id;

        if(count($relationsToDelete)){
            foreach ($relationsToDelete as $relation)
            {
                $model->$relation()->delete();
            }
        }

        return $model->delete();
    }

    public function getData(\Illuminate\Database\Eloquent\Builder|Builder $query, DataLimitService $dataLimit): Collection|\Illuminate\Support\Collection|LengthAwarePaginator
    {
        return $dataLimit->get($query);
    }
}
