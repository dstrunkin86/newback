<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\GetPostIndexRequest;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display posts list.
     */
    public function index(GetPostIndexRequest $request) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'id';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'desc';

        $result = Post::query()->where('is_published',1)->orderBy($sortField,$sortOrder)->paginate($pageSize);
        return $result;
    }

    /**
     * Display the specified post.
     */
    public function show(string $id)
    {
        $result = Post::findOrFail($id);
        return $result;
    }
}
