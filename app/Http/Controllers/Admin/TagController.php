<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Services\Admin\TagService;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    private const tagNames = [
        'style' => 'Стиль',
        'material' => 'Материал',
        'theme' => 'Тема',
        'genre' => 'Жанр',
        'technique' => 'Техника',
        'color' => 'Цвет',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->index();
    }

    /**
     * Display a tree listing of the resource.
     */
    public function treeIndex(TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->treeIndex();
    }

    /**
     * Display a select ready listing of the resource.
     */
    public function forSelectIndex(TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        $tags = $service->index()->toArray();

        foreach ($tags as $tag) {
            $s1[$tag['type']][] = $tag;
        }

        //dd($s1);

        foreach ($s1 as $key=>$el1) {
            $s2 = [];

            foreach($el1 as $el2) {
                $s2[] = [
                    "value" => $el2['id'],
                    "label" => $el2['title']->ru
                ];
            }

            $s3[] = [
                'label' => self::tagNames[$key],
                'options' => $s2
            ];
        }

        return $s3;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request, TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTagRequest $request, string $id, TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TagService $service)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return $service->delete($id);
    }
}
