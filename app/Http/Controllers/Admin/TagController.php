<?php

namespace App\Http\Controllers\Admin;

use App\Filters\TagFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
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
    public function index()
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Tag::with(['artworks'])->get();
    }

    /**
     * Display a tree listing of the resource.
     */
    public function treeIndex()
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $tags = Tag::with(['artworks'])->get(['id','type','title'])->toArray();
        $structure = [];
        foreach ($tags as $tag) {
            $tag['countArtworks'] = count($tag['artworks']);
            unset($tag['artworks']);
            $structure[$tag['type']][] = $tag;
        }
        return $structure;
    }

    /**
     * Display a select ready listing of the resource.
     */
    public function forSelectIndex(TagFilter $filter)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }

        $tags = Tag::filter($filter)->get();

        $s1 = [];
        foreach ($tags as $tag) {
            $s1[$tag['type']][] = $tag;
        }

        $s3 = [];
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
    public function store(StoreTagRequest $request)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Tag::query()->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Tag::query()->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTagRequest $request, string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Tag::query()->where('id', $id)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Tag::findOrFail($id)->delete();
    }
}
