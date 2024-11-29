<?php

namespace App\Filters;

use App\Filters\Filter;

class TagFilter extends Filter
{

    public function type_in($value)
    {
        $value = explode(",",$value);
        return $this->builder->whereIn('type', $value);
    }

}
