<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ArtikelReactionController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public newsletter subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Public landing page for guests; authenticated users redirect to the article list
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('artikel.index');
    }
    return view('welcome');
})->name('home');

// All protected routes: dashboard, profile, and articles (accessible only to logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('artikel.index');
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Articles routes (all behind auth)
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{id}', [ArtikelController::class, 'show'])->name('artikel.show');
    Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
    Route::get('/artikel/{id}/image', [ArtikelController::class, 'image'])->name('artikel.image');

    // Likes & Dislikes
    Route::post('/artikel/{id}/like', [ArtikelReactionController::class, 'like'])->name('artikel.like');
    Route::post('/artikel/{id}/dislike', [ArtikelReactionController::class, 'dislike'])->name('artikel.dislike');
    Route::delete('/artikel/{id}/reaction', [ArtikelReactionController::class, 'destroy'])->name('artikel.reaction.destroy');

    // Comments
    Route::post('/artikel/{id}/komentar', [KomentarController::class, 'store'])->name('komentar.store');
    Route::delete('/komentar/{id}', [KomentarController::class, 'destroy'])->name('komentar.destroy');

    // RSS Feed and XML Sitemap
    Route::get('/feed', [FeedController::class, 'feed'])->name('feed');
    Route::get('/sitemap.xml', [FeedController::class, 'sitemap'])->name('sitemap');
});

// Admin routes - auth + admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('users', UserController::class)->only(['index']);
    Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('comments', [KomentarController::class, 'index'])->name('comments.index');
    Route::delete('comments/{comment}', [KomentarController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';