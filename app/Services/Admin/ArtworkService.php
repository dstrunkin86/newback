<?php

namespace App\Services\Admin;

use App\Filters\Admin\ArtworkFilter;
use App\Models\Artwork;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class ArtworkService
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(ArtworkFilter $filter): \Illuminate\Database\Eloquent\Collection|array
    {
        return Artwork::with(['artist','tags:id,type,title','compilations:id,title'])->filter($filter)->get();
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $tags = [];
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }
        $compilations = [];
        if (isset($data['compilations'])) {
            $compilations = $data['compilations'];
            unset($data['compilations']);
        }
        $artwork = Artwork::query()->create($data);
        if (count($tags)>0) $artwork->tags()->sync($tags);
        if (count($compilations)>0) $artwork->compilations()->sync($compilations);

        return $artwork;
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return int
     */
    public function update(int $id, array $data): int
    {
        $artwork = Artwork::with(['tags','compilations'])->find($id);
        if (isset($data['tags'])) {
            $artwork->tags()->sync($data['tags']);
            unset($data['tags']);
        }
        if (isset($data['compilations'])) {
            $artwork->compilations()->sync($data['compilations']);
            unset($data['compilations']);
        }
        if (isset($data['images'])) {
            $artwork->updateImages($data['images']);
        }

        return $artwork->update($data);
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return Artwork::query()->where('id', $id)->delete();
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Artwork::query()->with(['artist','tags:id,type,title','compilations:id,title'])->find($id);
    }

}
