<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtikelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArtikelController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute ini BEBAS DIBUKA siapa saja
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
});


// Rute ini BEBAS DIBUKA siapa saja
Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');

// Rute ini TERGEMBOK! Hanya bisa diakses oleh Karyawan yang punya kunci (Sudah Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
});

require __DIR__.'/auth.php';