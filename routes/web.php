<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ArtikelReactionController;
use App\Http\Controllers\KomentarController;
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

// These routes are OPEN to everyone
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
});


// These routes are OPEN to everyone
Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');

// These routes are LOCKED! Only accessible to logged-in users
Route::middleware(['auth'])->group(function () {
    Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');

    // Like / dislike (toggle + switch), and remove reaction
    Route::post('/artikel/{id}/like', [ArtikelReactionController::class, 'like'])->name('artikel.like');
    Route::post('/artikel/{id}/dislike', [ArtikelReactionController::class, 'dislike'])->name('artikel.dislike');
    Route::delete('/artikel/{id}/reaction', [ArtikelReactionController::class, 'destroy'])->name('artikel.reaction.destroy');

    // Comments: create on an article, delete own (or admin)
    Route::post('/artikel/{id}/komentar', [KomentarController::class, 'store'])->name('komentar.store');
    Route::delete('/komentar/{id}', [KomentarController::class, 'destroy'])->name('komentar.destroy');
});

require __DIR__.'/auth.php';