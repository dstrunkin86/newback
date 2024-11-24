<?php

namespace App\Services\Admin;

use App\Models\Tag;

class TagService
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Tag::with(['artworks'])->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function treeIndex(): array
    {
        $tags = Tag::with(['artworks'])->get(['id','type','title'])->toArray();

        foreach ($tags as $tag) {
            $tag['countArtworks'] = count($tag['artworks']);
            unset($tag['artworks']);
            $structure[$tag['type']][] = $tag;
        }


        return $structure;
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Tag::query()->create($data);
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return int
     */
    public function update(int $id, array $data): int
    {
        return Tag::query()->where('id', $id)->update($data);
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return Tag::query()->where('id', $id)->delete();
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Tag::query()->find($id);
    }

}
