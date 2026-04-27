<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailySignalController;
use App\Http\Controllers\LatestScheduleController;
use App\Http\Controllers\ReferralLinkController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/media/settings/{field}', [SettingController::class, 'showFile'])->name('public.settings.files.show');
Route::get('/edukasi/artikel', [ArticleController::class, 'publicIndex'])->name('public.articles.index');
Route::get('/edukasi/video', [VideoController::class, 'publicIndex'])->name('public.videos.index');
Route::get('/buka-akun/{type}', [ReferralLinkController::class, 'publicIndex'])->name('public.referral-links.index');

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
    Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
    Route::get('/contents/create', [ContentController::class, 'create'])->name('contents.create');
    Route::post('/contents', [ContentController::class, 'store'])->name('contents.store');
    Route::get('/contents/{content}/edit', [ContentController::class, 'edit'])->name('contents.edit');
    Route::put('/contents/{content}', [ContentController::class, 'update'])->name('contents.update');
    Route::resource('/videos', VideoController::class)
        ->parameters(['videos' => 'video'])
        ->except(['show', 'create', 'edit']);
    Route::resource('/referral-links', ReferralLinkController::class)
        ->parameters(['referral-links' => 'referralLink'])
        ->except(['show', 'create', 'edit']);
    Route::resource('/latest-schedules', LatestScheduleController::class)
        ->parameters(['latest-schedules' => 'latestSchedule'])
        ->except(['show', 'create', 'edit']);
    Route::get('/daily-signals/{type}', [DailySignalController::class, 'index'])->name('daily-signals.index');
    Route::put('/daily-signals/{type}', [DailySignalController::class, 'update'])->name('daily-signals.update');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/files/{field}', [SettingController::class, 'showFile'])->name('settings.files.show');
    Route::delete('/settings/files/{field}', [SettingController::class, 'destroyFile'])->name('settings.files.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
