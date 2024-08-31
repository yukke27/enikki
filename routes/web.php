<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiaryController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/diaries', [DiaryController::class, 'index'])->name('index')->middleware('auth');
Route::get('/diaries/create', [DiaryController::class, 'create'])->name('create')->middleware('auth');
Route::post('/diaries', [DiaryController::class, 'store'])->name('store')->middleware('auth');
Route::get('/diaries/{diary}', [DiaryController::class, 'show'])->name('show')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
