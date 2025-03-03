<?php

namespace Soukar\Larepo\Services;

class DataLimitService
{
    const ALL = 'all';
    const PAGINATION = 'pagination';
    const TAKE = 'take';

    public function __construct(
        private $limitType = self::PAGINATION,
        private $paginationPageName = 'page',
        private $paginationPageItemsCount = 10,
        private $paginationPage=1,
        private $takeItemsCount = 10,
    )
    {

    }

    public function get(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query)
    {

        switch ($this->limitType)
        {
            case self::PAGINATION:
                return $this->getPaginationItems($query);
            case self::ALL:
                return $this->getAllItems($query);
            case self::TAKE:
                return $this->getLimitedItems($query);
        }
    }




    private  function getPaginationItems(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->paginate(
            perPage: $this->paginationPageItemsCount,
            pageName: $this->paginationPageName,
            page: $this->paginationPage
        );
    }

    private  function getAllItems(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->get();
    }

    private  function getLimitedItems(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->take($this->takeItemsCount)->get();
    }


    public function getCaheSuffix()
    {
        switch ($this->limitType)
        {
            case self::PAGINATION:
                return $this->getPaginationCacheSuffix();
            case self::ALL:
                return $this->getAllCacheSuffix();
            case self::TAKE:
                return $this->getLimitedCacheSuffix();
        }

    }


    private function getPaginationCacheSuffix()
    {
        return '_'.md5(self::PAGINATION.$this->paginationPage.$this->paginationPageName.$this->paginationPageItemsCount);
    }

    private function getAllCacheSuffix()
    {
        return '_'.md5(self::ALL);
    }

    private function getLimitedCacheSuffix()
    {
        return '_'.md5(self::TAKE.$this->takeItemsCount);
    }
}
