<?php

namespace App\Filters;

use App\Filters\Filter;

class UserFilter extends Filter
{

    public function email($value)
    {
        return $this->builder->where('email', $value);
    }

    public function role($value)
    {
        return $this->builder->role($value);
    }

}
