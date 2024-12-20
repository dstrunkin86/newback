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
        return $this->builder
            ->whereRaw('LOWER(fio->"$.ru") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(fio->"$.en") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(fio->"$.ar") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(fio->"$.cn") like "%'.mb_strtolower($value).'%"');
    }

    public function having_tags($value)
    {
        $value = explode(",",$value);

        return $this->builder->whereHas('tags', function ($query) use ($value) {
            $query->whereIn('id', $value);
        },'>=',count($value))->get();
    }

    public function city($value) {
        return $this->builder->where('city', '=', $value);
    }

}
