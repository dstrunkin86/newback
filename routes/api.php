<?php

use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\ArtworkController;
use App\Http\Controllers\Admin\CompilationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\User\UserController as Users;
use Illuminate\Support\Facades\Route;



Route::prefix('/admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/artists/{id}/add-image', [ArtistController::class, 'addImage']);
    Route::delete('/artists/{artistId}/delete-image/{imageId}', [ArtistController::class, 'deleteImage']);
    Route::resource('/artists', ArtistController::class);

    Route::post('/artworks/{id}/add-image', [ArtworkController::class, 'addImage']);
    Route::delete('/artworks/{artworkId}/delete-image/{imageId}', [ArtworkController::class, 'deleteImage']);
    Route::resource('/artworks', ArtworkController::class);

    Route::resource('/compilations', CompilationController::class);

    Route::post('/posts/{id}/add-image', [PostController::class, 'addImage']);
    Route::delete('/posts/{postId}/delete-image/{imageId}', [PostController::class, 'deleteImage']);
    Route::resource('/posts', PostController::class);


    Route::get('/tags/tree', [TagController::class, 'treeIndex']);
    Route::get('/tags/for-select', [TagController::class, 'forSelectIndex']);
    Route::resource('/tags', TagController::class);

    Route::resource('/users', UserController::class);

    Route::post('/general/store-image', [ImageController::class, 'store']);
    Route::post('/general/delete-image', [ImageController::class, 'destroy']);
});

// для старого фронта и приложения
Route::prefix('/v3')->group(function () {
    Route::post('/user', [Users::class, 'store']);
    Route::middleware('auth:sanctum')->group(function() {
        Route::patch('/user', [Users::class, 'update']);
    });
});

