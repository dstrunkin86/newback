<?php

namespace App\Services\Admin;

use App\Filters\Admin\UserFilter;
use App\Models\User;

class UserService
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(UserFilter $filter): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::with(['artist'])->filter($filter)->get();
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $user = User::query()->create($data);
        $user->syncRoles($data['role']);
        return $user;
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return int
     */
    public function update(int $id, array $data): int
    {
        $user = User::find($id);
        $user->syncRoles($data['role']);
        $user = $user->update($data);
        return $user;
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return User::query()->where('id', $id)->delete();
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return User::query()->find($id);
    }

}
