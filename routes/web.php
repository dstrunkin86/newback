<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Front\ArtworkController as FrontArtworkController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', function () {
    return view('login');
})->name('login');

Route::post('/admin/auth', [AdminAuthController::class, 'authenticate'])->name('authenticate');
Route::middleware('auth:sanctum')->get('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum')->get('/admin/{any_path?}', function (Request $request) {
    $data['userRole'] = $request->user()->role;
    return view('admin',$data);
})->where('any_path', '(.*)')->name('admin');
