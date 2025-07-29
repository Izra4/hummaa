<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
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
    
    // Materials Routes
    Route::get('/materi', function () {
        $allMateri = [
            [
                'id' => 1,
                'title' => 'Kompetensi Wawancara',
                'description' => 'Kumpulan soal dan jawaban wawancara untuk menguji integritas dan motivasi.',
                'status' => 'Progres',
                'duration' => '45 menit',
                'fileSize' => 'PDF 31 KB',
                'progress' => 50,
                'file_path' => 'materials/materi-wawancara.pdf'
            ],
            [
                'id' => 2,
                'title' => 'Kompetensi Sosio-Kultural',
                'description' => 'Bank soal pilihan ganda untuk mengukur kemampuan adaptasi dan interaksi sosial.',
                'status' => 'Selesai',
                'duration' => '90 menit',
                'fileSize' => 'PDF 145 KB',
                'progress' => 100,
                'file_path' => 'materials/materi-kompetensi-sosio-kultural.pdf'
            ],
            [
                'id' => 3,
                'title' => 'Kompetensi Manajerial',
                'description' => 'Materi konseptual tentang 8 aspek kompetensi manajerial, POAC, dan SWOT.',
                'status' => 'Progres',
                'duration' => '120 menit',
                'fileSize' => 'PDF 1.2 MB',
                'progress' => 25,
                'file_path' => 'materials/materi-manajerial.pdf'
            ]
        ];

        return view('material.materials-page', ['allMateri' => $allMateri]);
    })->name('materials');
    
    Route::get('/materi/{id}', function ($id) {
        return view('materi-detail', ['id' => $id]);
    })->name('materi.show')->where('id', '[0-9]+');
    
    // Forum
    Route::get('/forum', function () {
        return view('forum.page');
    })->name('forum');
});

// Public Routes that don't require authentication but might benefit from it
Route::middleware('web')->group(function () {
    // Add any routes that should be accessible to both guests and authenticated users
    // For example, public content, landing pages, etc.
});

// Include default auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';