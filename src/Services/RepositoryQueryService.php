<?php

    namespace Soukar\Larepo\Services;

class  RepositoryQueryService
{
    public function whereQuery($query,$whereFilters){
        foreach ($whereFilters as $key=>$value){
            if(is_numeric($key)){
                $key = array_key_first($value);
                $value = $value[$key];
            }

            if($this->isOperand($key) ){
                switch ($key){
                    case 'and':
                        $query->where(...$value);
                        break;
                    case 'or':
                        $query->orWhere(...$value);
                        break;
                    case 'andGroup' :
                        $query->where(function ($q) use($value){
                            foreach ($value as $subGroupFilter){
                                $this->whereQuery($q,$subGroupFilter);
                            }
                        });
                        break;
                    case 'orGroup' :
                        $query->orWhere(function ($q) use($value){
                            foreach ($value as $subGroupFilter){
                                $this->whereQuery($q,$subGroupFilter);
                            }
                        });
                        break;
                }
            }else{
                $query->where($key,$value);
            }

        }

    }

    private function isOperand($key)
    {
        return ($key=='and' || $key=='or' || $key=='andGroup' || $key=='orGroup');
    }
}
