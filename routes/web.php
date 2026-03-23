<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Manage\BannerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('App/Index');
})->name('index');

Route::post('locale/switch', function (Request $request) {
    $locale = $request->validate(['locale' => 'required|in:en,ru,tm'])['locale'];
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return response()->noContent();
})->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::prefix('manage')->name('manage.')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });
});

Route::middleware('auth')
    ->prefix('manage')
    ->name('manage.')
    ->group(function () {
        Route::get('/', fn() => Inertia::render('Manage/Index'))->name('index');

        Route::resource('banners', BannerController::class);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
