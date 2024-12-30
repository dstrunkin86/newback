<?php

namespace App\Filters;

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
        $lower = mb_strtolower($value);
        return $this->builder
            ->whereRaw('( LOWER(fio->"$.ru") like "%'.$lower.'%" OR LOWER(fio->"$.en") like "%'.$lower.'%" OR LOWER(fio->"$.ar") like "%'.$lower.'%" OR LOWER(fio->"$.cn") like "%'.$lower.'%" )');
    }

    public function having_tags($value)
    {
        $value = explode(",",$value);

        return $this->builder->whereHas('tags', function ($query) use ($value) {
            $query->whereIn('id', $value);
        },'>=',count($value));
    }

    public function city($value) {
        return $this->builder->whereJsonContains('city->value',$value);
    }

}
