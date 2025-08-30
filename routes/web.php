<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TryoutController;
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

// Public Routes
Route::get('/', function () {
    return view('home.welcome');
})->name('home');

// Google Auth Routes - Only for guests
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth-google-callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Protected Routes - Require Authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Management
    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });

    // Tryout Routes
    Route::prefix('tryout')->name('tryout.')->group(function () {
        Route::get('/', function () {
            return view('tryout.tryout-landing-page');
        })->name('index');

        Route::get('/{tryout_id}', [TryoutController::class, 'start'])->name('start');

        // !! TAMBAHKAN ROUTE INI !!
        // Route untuk MENERIMA SUBMIT jawaban dari pengguna (POST)
        Route::post('/submit/{attempt_id}', [TryoutController::class, 'submit'])->name('submit');

        // Route untuk MENAMPILKAN HALAMAN HASIL (GET)
        Route::get('/hasil/{attempt_id}', [TryoutController::class, 'showResult'])->name('result');
    });


    Route::get('/bank-soal', [BankSoalController::class, 'index'])->name('bank-soal');

    Route::get('/forum', function () {
        return view('forum.page');
    })->name('forum');

    Route::resource('materials', MateriController::class);

    Route::get('materials/{materi}/download', [MateriController::class, 'download'])->name('materials.download');
    Route::patch('materials/{materi}/progress', [MateriController::class, 'updateProgress'])->name('materials.updateProgress');
});

require __DIR__ . '/auth.php';
