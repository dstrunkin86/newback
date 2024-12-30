<?php

namespace App\Filters;

use App\Filters\Filter;

class OrderFilter extends Filter
{

    public function status_in($value)
    {
        $value = explode(",",$value);
        return $this->builder->whereIn('status', $value);
    }

}
