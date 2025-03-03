<?php

namespace Soukar\Larepo\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Soukar\Larepo\Services\DataLimitService;

interface RepositoryInterface
{
    public function getWhere(
        array $whereFilters = [],
        array $whereInFilters = [],
        DataLimitService $dataLimit = new DataLimitService(),
        array $relations = [],
        array $whereHasRelations = [],
        array $whereDoesNotHaveRelations = [],
        array $orderBy=[],
        array $joins = [],
        array $select = [],
        array $scopes = []
    ) : mixed;

    public function getWhereQuery(
        array $whereFilters = [],
        array $whereInFilters = [],
        DataLimitService $dataLimit = new DataLimitService(),
        array $relations = [],
        array $whereHasRelations = [],
        array $whereDoesNotHaveRelations = [],
        array $orderBy=[],
        array $joins = [] ,
        array $select = [],
        array $scopes = []
    ) : Builder|\Illuminate\Database\Eloquent\Builder;

    public function getData(Builder|\Illuminate\Database\Eloquent\Builder $query , DataLimitService $dataLimit):Collection|\Illuminate\Support\Collection|LengthAwarePaginator;

    public function findById(
        $id ,
        array $relations=[]
    );

    public function findWhere(
        array $whereFilters ,
        array $relations=[],
    );

    public function add(
        DataObjectTransfer|array $data
    ): Model;
    public function update(
        DataObjectTransfer|array $data ,
        $id
    ): bool;
    public function delete(
        Model | string $id ,
        array $relationsToDelete =[]
    ): bool;

}
