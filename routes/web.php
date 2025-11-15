<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if ($request->user()) {
        return redirect()->route('pos');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::view('/pos', 'pos')->middleware('role:admin|cashier')->name('pos');
    Route::view('/kds', 'kds')->middleware('role:admin|kitchen')->name('kds');
    Route::view('/recipes', 'recipes')->middleware('role:admin|kitchen')->name('recipes');
    Route::view('/tables', 'tables')->middleware('role:admin|cashier')->name('tables');
    Route::view('/reports', 'reports')->middleware('role:admin|kitchen|inventory')->name('reports');
    Route::view('/inventory', 'inventory')->middleware('role:admin|inventory')->name('inventory');

    Route::get('/dashboard', function () {
        return redirect()->route('pos');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Extra admin-only routes go here as needed

require __DIR__.'/auth.php';
