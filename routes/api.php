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
use App\Http\Controllers\OldArthallHooksController;

Route::prefix('/general')->middleware('auth:sanctum')->group(function () {
    Route::post('/store-image', [ImageController::class, 'store']);
    Route::post('/delete-image', [ImageController::class, 'destroy']);
});

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

});

// для старого фронта и приложения
Route::prefix('/v3')->group(function () {

    Route::post('/user', [OldArthallHooksController::class, 'registerUser']);

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user/settings', [OldArthallHooksController::class, 'userSEttings']);
        Route::patch('/user', [OldArthallHooksController::class, 'updateUser']);

        Route::get('/app/mobile-lang/{id}', [OldArthallHooksController::class, 'mobileLang']);

        Route::get('/notifications/list', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/paintings/in-sale', [OldArthallHooksController::class, 'emptyArray']);
        Route::get('/paintings/list', [OldArthallHooksController::class, 'artworksList']);
        Route::get('/paintings/web-list-synergy', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/paintings/{id}/share', [OldArthallHooksController::class, 'returnSuccess']);
        Route::post('/paintings/{id}/set-viewed', [OldArthallHooksController::class, 'setArtworkViewed']);
        Route::patch('/paintings/{id}/set-rate', [OldArthallHooksController::class, 'setArtworkRate']);
        Route::get('/paintings/{id}', [OldArthallHooksController::class, 'artworkDetail']);

        Route::get('/artists/favourites', [OldArthallHooksController::class, 'emptyArray']);
        Route::get('/artists/list', [OldArthallHooksController::class, 'artistsList']);
        Route::post('/artists/{id}/attitude', [OldArthallHooksController::class, 'artistAttitude']);
        Route::post('/artists/{id}/share', [OldArthallHooksController::class, 'returnSuccess']);
        Route::get('/artists/{id}', [OldArthallHooksController::class, 'artistDetail']);

        Route::get('galleries/own', [OldArthallHooksController::class, 'ownGalleries']);

    });
});

