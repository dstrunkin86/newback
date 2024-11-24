<?php

namespace App\Filters\Admin;

use App\Filters\Filter;

class ArtworkFilter extends Filter
{

    public function status_in($value)
    {
        $value = explode(",",$value);
        return $this->builder->whereIn('status', $value);
    }

    public function title($value)
    {
        return $this->builder->whereJsonContains('fio->ru', $value)->get();
    }

    public function havingTags($value)
    {
        $value = explode(",",$value);

        return $this->builder->whereHas('tags', function ($query) use ($value) {
            $query->whereIn('tag.id', $value);
        })->get();
    }

    public function artist($value)
    {
        return $this->builder->whereRelation('artist', 'id', $value)->get();
    }

    public function in_sale($value)
    {
        return $this->builder->where('in_sale', $value)->get();
    }

    public function price_from($value)
    {
        return $this->builder->where('price', '>=', $value)->get();
    }

    public function price_to($value)
    {
        return $this->builder->where('price', '<=', $value)->get();
    }

}
