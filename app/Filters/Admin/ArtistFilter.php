<?php

namespace App\Filters\Admin;

use App\Filters\Filter;

class ArtistFilter extends Filter
{

    public function source($value)
    {
        return $this->builder->where('source', $value);
    }

    public function status_in($value)
    {
        $value = explode(",",$value);
        return $this->builder->whereIn('status', $value);
    }

    public function fio($value)
    {
        return $this->builder->whereJsonContains('fio->ru', $value)->get();
    }

}
