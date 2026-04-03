<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Manage\AboutController;
use App\Http\Controllers\Manage\BannerController;
use App\Http\Controllers\Manage\ClientController;
use App\Http\Controllers\Manage\ContactController;
use App\Http\Controllers\Manage\CounterController;
use App\Http\Controllers\Manage\DashboardController;
use App\Http\Controllers\Manage\ManufacturerController;
use App\Http\Controllers\Manage\ProductController;
use App\Http\Controllers\Manage\ServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WebController::class, 'index'])->name('index');

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
        // Route::get('/', fn() => Inertia::render('Manage/Index'))->name('index');
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::get('banners/sort-order', [BannerController::class, 'sortOrderForm'])->name('banners.sortOrder.form');
        Route::patch('banners/sort-order', [BannerController::class, 'sortOrder'])->name('banners.sortOrder');
        Route::resource('banners', BannerController::class)->except('show');

        Route::resource('about', AboutController::class)->middleware('single.about')->except(['show', 'destroy']);

        Route::get('counters/sort-order', [CounterController::class, 'sortOrderForm'])->name('counters.sortOrder.form');
        Route::patch('counters/sort-order', [CounterController::class, 'sortOrder'])->name('counters.sortOrder');
        Route::resource('counters', CounterController::class)->except(['show']);

        Route::get('services/sort-order', [ServiceController::class, 'sortOrderForm'])->name('services.sortOrder.form');
        Route::patch('services/sort-order', [ServiceController::class, 'sortOrder'])->name('services.sortOrder');
        Route::resource('services', ServiceController::class)->except(['show']);

        Route::get('manufacturers/sort-order', [ManufacturerController::class, 'sortOrderForm'])->name('manufacturers.sortOrder.form');
        Route::patch('manufacturers/sort-order', [ManufacturerController::class, 'sortOrder'])->name('manufacturers.sortOrder');
        Route::resource('manufacturers', ManufacturerController::class)->except(['show']);

        Route::get('products/sort-order', [ProductController::class, 'sortOrderForm'])->name('products.sortOrder.form');
        Route::patch('products/sort-order', [ProductController::class, 'sortOrder'])->name('products.sortOrder');
        Route::resource('products', ProductController::class)->except(['show']);

        Route::get('clients/sort-order', [ClientController::class, 'sortOrderForm'])->name('clients.sortOrder.form');
        Route::patch('clients/sort-order', [ClientController::class, 'sortOrder'])->name('clients.sortOrder');
        Route::resource('clients', ClientController::class)->except(['show']);

        Route::resource('contacts', ContactController::class)->only(['index', 'edit', 'update']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
