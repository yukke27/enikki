<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [DiaryController::class, 'index'])->name('index')->middleware('auth');
Route::get('/diaries/create', [DiaryController::class, 'create'])->name('create')->middleware('auth');
Route::post('/diaries', [DiaryController::class, 'store'])->name('store')->middleware('auth');
Route::get('/diaries/{diary}/edit', [DiaryController::class, 'edit'])->name('edit')->middleware('auth');
Route::put('/diaries/{diary}', [DiaryController::class, 'update'])->name('update')->middleware('auth');
Route::get('/diaries/gallery', [DiaryController::class, 'gallery'])->name('gallery')->middleware('auth');

Route::post('/tags/search', [TagController::class, 'index'])->middleware('auth');

Route::post('/api/favorites', [FavoriteController::class, 'store'])->name('favorites.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
