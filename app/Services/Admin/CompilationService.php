<?php

namespace App\Services\Admin;

use App\Models\Compilation;

class CompilationService
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Compilation::with(['artworks'])->get();
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Compilation::query()->create($data);
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return int
     */
    public function update(int $id, array $data): int
    {
        return Compilation::query()->where('id', $id)->update($data);
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return Compilation::query()->where('id', $id)->delete();
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Compilation::query()->find($id);
    }

}
