<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryTrait
{
    // Filtrar
    function scopeFilter(Builder $builder)
    {
        // ?filter[surname]=vargas&filter[age]=20
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $builder->orWhere($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }

    // Incluir
    function scopeIncluded(Builder $builder)
    {
        // &included=workStatus,user
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $value) {
            if (!$allowIncluded->contains($value)) {
                unset($relations[$key]);
            }

            $builder->with($relations);
        }
    }

    // Resultado
    function scopeResult(Builder $builder)
    {
        // result=5/all
        $result = request('result');
        if ($result) {
            if ($result == 'all') {
                return $builder->get();
            }
            if (is_numeric($result)) {
                return $builder->paginate(number_format($result));
            }
        }
        return $builder->paginate(10);
    }

    // Ordenar
    function scopeSort(Builder $builder)
    {
        // &sort=-id (descendente) || &sort=id (ascendente)
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if (substr($sortField, 0, 1) == '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }
            if ($allowSort->contains($sortField)) {
                $builder->orderBy($sortField, $direction);
            }
        }
    }
}
