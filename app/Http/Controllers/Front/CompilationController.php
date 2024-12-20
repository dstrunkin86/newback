<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\GetCompilationIndexRequest;
use App\Models\Compilation;
use Illuminate\Http\Request;

class CompilationController extends Controller
{
    /**
     * Display compilations list.
     */
    public function index(GetCompilationIndexRequest $request) {

        $pageSize = (isset($request->page_size)) ? $request->page_size : 12;

        $sortField = (isset($request->sort_field)) ? $request->sort_field : 'priority';

        $sortOrder = (isset($request->sort_order)) ? $request->sort_order : 'asc';

        $result = Compilation::query()->where('is_published',1)->orderBy($sortField,$sortOrder)->paginate($pageSize);
        return $result;
    }

    /**
     * Display the specified compilation.
     */
    public function show(string $id)
    {
        $compilation = Compilation::with(['artworks'])->findOrFail($id);
        return $compilation;
    }
}
