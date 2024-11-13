<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/auth', [AuthController::class, 'authenticate'])->name('authenticate');
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum')->get('/admin/{any_path?}', function (Request $request) {
    $data['userRole'] = $request->user()->role;
    return view('admin',$data);
})->where('any_path', '(.*)')->name('admin');
