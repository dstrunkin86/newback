<?php

use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\ArtworkController;
use App\Http\Controllers\Admin\CompilationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;



Route::prefix('/admin')->middleware('auth:sanctum')->group(function () {
    Route::resource('/artists', ArtistController::class);
    Route::resource('/artworks', ArtworkController::class);
    Route::resource('/compilations', CompilationController::class);
    Route::resource('/posts', PostController::class);
    Route::resource('/images', ImageController::class);
    Route::get('/tags/tree', [TagController::class, 'treeIndex']);
    Route::get('/tags/for-select', [TagController::class, 'forSelectIndex']);
    Route::resource('/tags', TagController::class);
    Route::resource('/users', UserController::class);
});

Route::middleware('auth:sanctum')->post('/general/store-image', [ImageController::class, 'store']);
Route::middleware('auth:sanctum')->post('/general/delete-image', [ImageController::class, 'destroy']);
