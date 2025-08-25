<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MateriController;
use \App\Http\Controllers\DiscussionController;
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
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Tryout Routes
    Route::prefix('tryout')->group(function () {
        Route::get('/', function () {
            return view('tryout.tryout-landing-page');
        })->name('tryouts');

        Route::get('/details', function () {
            return view('tryout.tryout-page');
        })->name('tryout-detail');

        Route::get('/hasil', function () {
            return view('tryout.tryout-completed-page');
        })->name('tryout-completed');
    });

    // Bank Soal
    Route::get('/bank-soal', function () {
        return view('bank-soal.bank-soal-page');
    })->name('bank-soal');


    // Forum
    Route::get('/forum', [DiscussionController::class, 'index'])->name('forum');

    // Materials route
    Route::resource('materials', MateriController::class);

    Route::get('materials/{materi}/download', [MateriController::class, 'download'])->name('materials.download');
    Route::patch('materials/{materi}/progress', [MateriController::class, 'updateProgress'])->name('materials.updateProgress');
});

// Public Routes that don't require authentication but might benefit from it
Route::middleware('web')->group(function () {
    // Add any routes that should be accessible to both guests and authenticated users
    // For example, public content, landing pages, etc.
});

// Include default auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
