<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OldArthallHooksController;

use App\Http\Controllers\ImageController as GeneralImageController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\CompilationController as AdminCompilationController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\Front\ArtistController as FrontArtistController;
use App\Http\Controllers\Front\ArtworkController as FrontArtworkController;
use App\Http\Controllers\Front\SettingsController as FrontSettingsController;
use App\Http\Controllers\Front\CompilationController as FrontCompilationController;
use App\Http\Controllers\Front\PostController as FrontPostController;
use App\Http\Controllers\Front\UsersController as FrontUsersController;

// общие методы для админки и ЛК художников
Route::prefix('/general')->middleware('auth:sanctum')->group(function () {
    Route::post('/store-image', [GeneralImageController::class, 'store']);
    Route::post('/delete-image', [GeneralImageController::class, 'destroy']);
});

// методы для фронтов
Route::post('/users', [FrontUsersController::class, 'register']);
Route::middleware('auth:sanctum')->patch('/users', [FrontUsersController::class, 'update']);

Route::get('/artists', [FrontArtistController::class, 'index']);
Route::get('/artists/{id}', [FrontArtistController::class, 'show']);

Route::get('/compilations', [FrontCompilationController::class, 'index']);
Route::get('/compilations/{id}', [FrontCompilationController::class, 'show']);

Route::get('/posts', [FrontPostController::class, 'index']);
Route::get('/posts/{id}', [FrontPostController::class, 'show']);

Route::get('/artworks', [FrontArtworkController::class, 'index']);
Route::get('/artworks/{id}', [FrontArtworkController::class, 'show']);

Route::get('/settings/tags-tree', [FrontSettingsController::class, 'tagsTree']);
Route::get('/settings/available-artist-cities', [FrontSettingsController::class, 'artistCities']);
Route::get('/settings/available-artwork-cities', [FrontSettingsController::class, 'artworkCities']);

//админка
Route::prefix('/admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/artists/{id}/add-image', [AdminArtistController::class, 'addImage']);
    Route::delete('/artists/{artistId}/delete-image/{imageId}', [AdminArtistController::class, 'deleteImage']);
    Route::resource('/artists', AdminArtistController::class);

    Route::post('/artworks/{id}/add-image', [AdminArtworkController::class, 'addImage']);
    Route::delete('/artworks/{artworkId}/delete-image/{imageId}', [AdminArtworkController::class, 'deleteImage']);
    Route::resource('/artworks', AdminArtworkController::class);

    Route::resource('/compilations', AdminCompilationController::class);

    Route::post('/posts/{id}/add-image', [AdminPostController::class, 'addImage']);
    Route::delete('/posts/{postId}/delete-image/{imageId}', [AdminPostController::class, 'deleteImage']);
    Route::resource('/posts', AdminPostController::class);


    Route::get('/tags/tree', [AdminTagController::class, 'treeIndex']);
    Route::get('/tags/for-select', [AdminTagController::class, 'forSelectIndex']);
    Route::resource('/tags', AdminTagController::class);

    Route::resource('/users', AdminUserController::class);

});

// для старого фронта и приложения
Route::prefix('/v3')->group(function () {

    Route::post('/user', [OldArthallHooksController::class, 'registerUser']);
    Route::get('/paintings/web-list', [OldArthallHooksController::class, 'artworksList']);
    Route::get('/artists/list', [OldArthallHooksController::class, 'artistsList']);
    Route::get('/artists/top', [OldArthallHooksController::class, 'emptyArray']);
    Route::get('/paintings/top', [OldArthallHooksController::class, 'emptyArray']);
    Route::get('/app/news', [OldArthallHooksController::class, 'emptyArray']);

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user/settings', [OldArthallHooksController::class, 'userSettings']);
        Route::patch('/user', [OldArthallHooksController::class, 'updateUser']);

        Route::get('/app/mobile-lang/{id}', [OldArthallHooksController::class, 'mobileLang']);
        Route::get('/app/desktop-lang/{id}', [OldArthallHooksController::class, 'desktopLang']);
        Route::get('/app/business-links', [OldArthallHooksController::class, 'emptyArray']);


        Route::get('/notifications/list', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/stories/list', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/articles/list', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/paintings/in-sale', [OldArthallHooksController::class, 'emptyArray']);
        Route::get('/paintings/list', [OldArthallHooksController::class, 'artworksList']);
        Route::get('/paintings/web-list-synergy', [OldArthallHooksController::class, 'emptyArray']);

        Route::get('/paintings/{id}/share', [OldArthallHooksController::class, 'returnSuccess']);
        Route::post('/paintings/{id}/set-viewed', [OldArthallHooksController::class, 'setArtworkViewed']);
        Route::patch('/paintings/{id}/set-rate', [OldArthallHooksController::class, 'setArtworkRate']);
        Route::get('/paintings/{id}', [OldArthallHooksController::class, 'artworkDetail']);

        Route::get('/artists/favourites', [OldArthallHooksController::class, 'emptyArray']);
        Route::post('/artists/{id}/attitude', [OldArthallHooksController::class, 'artistAttitude']);
        Route::post('/artists/{id}/share', [OldArthallHooksController::class, 'returnSuccess']);
        Route::get('/artists/{id}', [OldArthallHooksController::class, 'artistDetail']);

        Route::get('galleries/own', [OldArthallHooksController::class, 'ownGalleries']);

    });
});

