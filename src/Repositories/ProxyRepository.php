<?php

namespace Soukar\Larepo\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Soukar\Larepo\Interfaces\RepositoryHasRelatedModels;
use Soukar\Larepo\Interfaces\RepositoryInterface;
use Soukar\Larepo\Interfaces\ShouldCache;
use Soukar\Larepo\Services\DataLimitService;

class ProxyRepository implements RepositoryInterface
{

    public function __construct(
        private RepositoryInterface $repository,
        private Model $model
    )
    {
    }



    public function getWhere(array $whereFilters = [], array $whereInFilters = [], DataLimitService $dataLimit = new DataLimitService(), array $relations = [], array $whereHasRelations = [], array $whereDoesNotHaveRelations = [], array $orderBy = [], array $joins = [], array $select =[] , array $scopes = []): mixed
    {

        $query = $this->repository->getWhereQuery(
            whereFilters :$whereFilters,
            whereInFilters:  $whereInFilters,
            dataLimit: $dataLimit,
            relations: $relations,
            whereHasRelations: $whereHasRelations,
            whereDoesNotHaveRelations: $whereDoesNotHaveRelations,
            orderBy: $orderBy,
            joins: $joins,
            select: $select,
            scopes: $scopes
        );

        if($this->shouldDoCache()){
            $suffix = $dataLimit->getCaheSuffix().'_'.$this->getRelationsCacheKey($relations).'_'.$this->getScopesCacheKey($scopes);
            $cacheKey = $this->getCacheKey($query , $suffix);
            return Cache::tags([$this->getCacheTag()])->rememberForever($cacheKey,
                fn()=> $dataLimit->get($query)
            );
        }else{
            return $dataLimit->get($query);
        }
    }

    public function findById($id, array $relations = [])
    {
        if($this->shouldDoCache())
        {
            return Cache::tags([$this->getCacheTag()])->rememberForever($this->getCacheTag().$id,
                fn()=>$this->repository->findById($id,$relations)
            );
        }
        return $this->repository->findById($id,$relations);
    }

    public function findWhere(array $whereFilters, array $relations = [],)
    {
        $query = $this->repository->getWhereQuery(
            whereFilters :$whereFilters,
            relations: $relations
        );

        if($this->shouldDoCache()){
            $suffix = "-resource";
            $cacheKey = $this->getCacheKey($query , $suffix);
            return Cache::tags([$this->getCacheTag()])->rememberForever($cacheKey,
                fn()=> $query->first()
            );
        }else{
            return $query->first();
        }
    }

    public function add(array $data): Model
    {
        $model = $this->repository->add($data);
        if($this->shouldDoCache())
            $this->flushCache();
        return $model;

    }

    public function update(array $data, $id): bool
    {
        $model = $this->repository->update($data,$id);
        if($this->shouldDoCache())
            $this->flushCache();
        return $model;
    }

    public function delete($id, array $relationsToDelete = []): bool
    {
        if($this->shouldDoCache())
            $this->flushCache();

        return $this->repository->delete($id,$relationsToDelete);
    }

    private function shouldDoCache()
    {
        return $this->repository instanceof ShouldCache;
    }


    private function getCacheTag($modelCLass = NULL)
    {
        return  class_basename($modelCLass ?? $this->model);
    }

    public function flushCache()
    {
        $tags = [
            $this->getCacheTag()
        ];
        if($this->repository instanceof RepositoryHasRelatedModels){
            foreach ($this->repository->getRelatedModels() as $modelClass)
            {
                $tags[]=$this->getCacheTag($modelClass);
            }
        }
        Cache::tags($tags)->flush();
    }

    private function getCacheKey(Builder|\Illuminate\Database\Eloquent\Builder $query , $cacheSuffix)
    {
        $sql = $query->toRawSql();
        $cacheTag = $this->getCacheTag();
        $cacheKey = $cacheTag.'_'.md5($sql);
        return $cacheKey . md5($cacheSuffix);
    }

    private function getRelationsCacheKey($relations){
        $key = '';

        foreach ($relations as $index => $value)
        {
            $key.=is_numeric($index) ? $value : $index;
        }

        return md5($key);
    }
    private function getScopesCacheKey($scopes){
        $key = '';

        foreach ($scopes as $scope)
        {
            $key.=$scope;
        }

        return md5($key);
    }


    public function getWhereQuery(array $whereFilters = [], array $whereInFilters = [], DataLimitService $dataLimit = new DataLimitService(), array $relations = [], array $whereHasRelations = [], array $whereDoesNotHaveRelations = [], array $orderBy = [], array $joins = [], array $select = [] , array $scopes = []): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return $this->repository->getWhereQuery($whereFilters , $whereInFilters , $dataLimit ,$relations , $whereHasRelations , $whereDoesNotHaveRelations , $orderBy , $joins , $select , $scopes);
    }

    public function getData(\Illuminate\Database\Eloquent\Builder|Builder $query, DataLimitService $dataLimit): Collection|\Illuminate\Support\Collection|LengthAwarePaginator
    {
        if($this->shouldDoCache()){
            return Cache::tags([$this->getCacheTag()])->rememberForever($this->getCacheKey($query , $dataLimit->getCaheSuffix()),
                fn()=> $this->repository->getData($query,$dataLimit)
            );
        }else{
            return $this->repository->getData($query,$dataLimit);
        }
    }
}
