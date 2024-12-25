<?php

namespace App\Filters;

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
        return $this->builder
            ->whereRaw('LOWER(title->"$.ru") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(title->"$.en") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(title->"$.ar") like "%'.mb_strtolower($value).'%"')
            ->orWhereRaw('LOWER(title->"$.cn") like "%'.mb_strtolower($value).'%"');
    }

    public function having_tags($value)
    {
        $value = explode(",",$value);

        return $this->builder->whereHas('tags', function ($query) use ($value) {
            $query->whereIn('id', $value);
        },'>=',count($value))->get();
    }

    public function artist_id($value)
    {
        return $this->builder->where('artist_id', $value)->get();
    }

    public function in_sale($value)
    {
        return $this->builder->where('in_sale', intval($value))->get();
    }

    public function price_from($value)
    {
        return $this->builder->where('price', '>=', intval($value))->where('in_sale',1)->get();
    }

    public function price_to($value)
    {
        return $this->builder->where('price', '<=', intval($value))->where('in_sale',1)->get();
    }

    public function width_from($value) {
        return $this->builder->where('width', '>=', intval($value));
    }

    public function width_to($value) {
        return $this->builder->where('width', '<=', intval($value));
    }

    public function height_from($value) {
        return $this->builder->where('height', '>=', intval($value));
    }

    public function height_to($value) {
        return $this->builder->where('height', '<=', intval($value));
    }

    public function year_from($value) {
        return $this->builder->where('year', '>=', intval($value));
    }

    public function year_to($value) {
        return $this->builder->where('year', '<=', intval($value));
    }

    public function location($value) {
        return $this->builder->whereJsonContains('location->city',$value);
    }
}
