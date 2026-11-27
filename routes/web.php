<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/tentang', [HomeController::class, 'tentang']);
Route::get('/kontak', [HomeController::class, 'kontak']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Route profil bawaan Breeze (dipakai oleh AuthenticatedLayout)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route AJAX (JSON) HARUS ditulis DI ATAS Route::resource (lihat E.5)
    Route::get('books/search', [BookController::class, 'search'])
        ->name('books.search');
    Route::get('members/search', [MemberController::class, 'search'])
        ->name('members.search');
    Route::get('members/cek-nis', [MemberController::class, 'cekNis'])
        ->name('members.cek-nis');

    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
});

require __DIR__.'/auth.php';