<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\ProfileController;
use App\Models\Menu;
use App\Models\Category; // Imported to handle our relational category queries
use App\Models\Announcement;
use App\Models\Gallery;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC LANDING ROUTES
|--------------------------------------------------------------------------
| These routes are accessible by any visitor to the Kawa Lipa platform.
*/

Route::get('/', function (Request $request) {
    // Eager-load 'category' to prevent N+1 issues and supply relations to Welcome.vue
    $menus = Menu::with('category')->get();
    
    // FIXED: Pluck the string names directly from your new categories database table
    $categories = Category::pluck('name')->filter()->values();
    
    $announcements = Announcement::latest()->get();
    $gallery = Gallery::latest()->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'menus' => $menus,
        'categories' => $categories,
        'announcements' => $announcements,
        'gallery' => $gallery,
    ]);
});

/*
|--------------------------------------------------------------------------
| 2. STANDARD PROTECTED ROUTES (AUTHENTICATED USERS)
|--------------------------------------------------------------------------
| Core entry points and profile account management operations.
*/

// Main Entry Dashboard - Points directly to the DashboardController engine
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Account Profile Configuration Sub-stack
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| 3. ADMINISTRATIVE CONTROL ROUTE GROUP (PREFIX: /admin)
|--------------------------------------------------------------------------
| High-level configurations restricted to authenticated web supervisors.
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // --- MODULE: MENUS CATALOG ---
    // Single optimization declaration handling complete CRUD lifecycle (Index, Store, Update, Destroy)
    Route::resource('menus', MenuController::class);

    // --- MODULE: CATEGORIES MANAGEMENT ---
    // Using resource automatically handles index, create, store, edit, update, and destroy 
    // mapping them directly to your CategoryController methods.
    Route::resource('categories', CategoryController::class);

    // --- MODULE: ANNOUNCEMENTS ---
    Route::resource('announcements', AnnouncementController::class);

    // --- MODULE: VIBE GALLERY ---
    Route::resource('gallery', GalleryController::class);

});

/*
|--------------------------------------------------------------------------
| 4. REGISTRATION & SESSION MANAGEMENT
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';