<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/accounts', AccountController::class)
        ->parameters(['accounts' => 'account'])
        ->except(['show', 'create', 'edit']);
    Route::resource('/categories', CategoryController::class)
        ->parameters(['categories' => 'category'])
        ->except(['show', 'create', 'edit']);
    Route::resource('/articles', ArticleController::class)
        ->parameters(['articles' => 'article'])
        ->except(['show']);
    Route::resource('/videos', VideoController::class)
        ->parameters(['videos' => 'video'])
        ->except(['show', 'create', 'edit']);
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/files/{field}', [SettingController::class, 'showFile'])->name('settings.files.show');
    Route::delete('/settings/files/{field}', [SettingController::class, 'destroyFile'])->name('settings.files.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
