<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait FiltersByMonth
{
    public function scopeForMonth(Builder $query, int $year, int $monthNum, string $column = 'date'): Builder
    {
        return $query->whereYear($column, $year)->whereMonth($column, $monthNum);
    }
}

