<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC LANDING ROUTES
|--------------------------------------------------------------------------
| These routes are accessible by any visitor to the Kawa Lipa platform.
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| 2. STANDARD PROTECTED ROUTES (AUTHENTICATED USERS)
|--------------------------------------------------------------------------
| Core entry points and profile account management operations.
*/

// Main Entry Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    // These lines provide the explicit named route hooks your Vue Index component requires
    Route::get('/categories/create', function () {
        return Inertia::render('Admin/Menus/CreateCategory');
    })->name('categories.create');

    // Add this line right here:
    Route::post('/categories', [MenuController::class, 'storeCategory'])->name('categories.store');

    // Add this new edit layout view route line:
    Route::get('/categories/{name}/edit', [MenuController::class, 'editCategory'])->name('categories.edit');

    Route::put('/categories/{name}', [MenuController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{name}', [MenuController::class, 'destroyCategory'])->name('categories.destroy');


    
    // --- MODULE: ANNOUNCEMENTS ---
    // (Future endpoint definitions go here)

    // --- MODULE: VIBE GALLERY ---
    // (Future endpoint definitions go here)

});

/*
|--------------------------------------------------------------------------
| 4. REGISTRATION & SESSION MANAGEMENT
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';