<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TryoutController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BankSoalController;


use App\Http\Middleware\ValidateTryoutSession;
use App\Http\Middleware\CheckTryoutMode;
use App\Http\Middleware\ThrottleTryoutActions;

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

    // Event Routes
    Route::prefix('event')->group(function () {
        Route::get('/', function () {
            return view('tryout.event-landing-page');
        })->name('events');
    });

    // Bank Soal Routes
    Route::prefix('bank-soal')->group(function () {
        // Main bank soal page
        Route::get('/', [BankSoalController::class, 'index'])->name('bank-soal');
        
        // API endpoints for dynamic content
        Route::get('/api/tryouts/{type}', [BankSoalController::class, 'getTryoutsByType'])
            ->name('bank-soal.api.tryouts-by-type');
    });

    // Tryout Routes
    Route::prefix('tryout')->middleware([ThrottleTryoutActions::class])->group(function () {
        // Tryout landing page
        Route::get('/', [TryoutController::class, 'index'])->name('tryouts');
        
        // Tryout execution page
        Route::get('/details', [TryoutController::class, 'show'])->name('tryout-detail');
        
        // User tryout history
        Route::get('/history', [TryoutController::class, 'history'])->name('tryout-history');
        
        // API Routes for tryout functionality
        Route::prefix('api')->group(function () {
            // Start a new tryout session
            Route::post('/start', [TryoutController::class, 'start'])
                ->name('tryout.api.start');
            
            // Get current session data
            Route::get('/session/{sessionId}', [TryoutController::class, 'getSession'])
                ->middleware([ValidateTryoutSession::class])
                ->name('tryout.api.session');
            
            // Save individual answer (with auto-save)
            Route::post('/answer', [TryoutController::class, 'saveAnswer'])
                ->middleware([ValidateTryoutSession::class])
                ->name('tryout.api.save-answer');
            
            // Submit tryout
            Route::post('/submit', [TryoutController::class, 'submit'])
                ->middleware([ValidateTryoutSession::class, CheckTryoutMode::class . ':tryout'])
                ->name('tryout.api.submit');
        });
        
        // Tryout results page
        Route::get('/hasil/{resultId}', [TryoutController::class, 'result'])
            ->name('tryout-completed')
            ->where('resultId', '[0-9]+');
    });

    // Materials Routes
    Route::prefix('materials')->group(function () {
        // Resource routes for materials - Fixed
        Route::get('/', [MateriController::class, 'index'])->name('materials.index');
        Route::get('/create', [MateriController::class, 'create'])->name('materials.create');
        Route::post('/', [MateriController::class, 'store'])->name('materials.store');
        Route::get('/{materi}', [MateriController::class, 'show'])->name('materials.show');
        Route::get('/{materi}/edit', [MateriController::class, 'edit'])->name('materials.edit');
        Route::put('/{materi}', [MateriController::class, 'update'])->name('materials.update');
        Route::delete('/{materi}', [MateriController::class, 'destroy'])->name('materials.destroy');
        
        // Additional material routes
        Route::get('/{materi}/download', [MateriController::class, 'download'])
            ->name('materials.download');
        
        // API Routes for material functionality
        Route::prefix('api')->group(function () {
            // Update user progress
            Route::patch('/{materialId}/progress', [MateriController::class, 'updateProgress'])
                ->name('materials.api.update-progress');
            
            // Mark material as completed
            Route::post('/{materialId}/complete', [MateriController::class, 'markCompleted'])
                ->name('materials.api.complete');
            
            // Get materials by category
            Route::get('/category/{categoryId}', [MateriController::class, 'getByCategory'])
                ->name('materials.api.by-category');
            
            // Search materials
            Route::get('/search', [MateriController::class, 'search'])
                ->name('materials.api.search');
            
            // Get user progress
            Route::get('/progress', [MateriController::class, 'getUserProgress'])
                ->name('materials.api.user-progress');
        });
    });

    // Forum Routes
    Route::get('/forum', function () {
        return view('forum.page');
    })->name('forum');
});

/*
// Admin Routes (if needed in the future)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    // Admin dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Admin tryout management
    Route::resource('tryouts', TryoutController::class);
    
    // Admin question management
    Route::resource('questions', QuestionController::class);
    
    // Admin material management
    Route::resource('materials', MaterialController::class);
    
    // Admin user management
    Route::resource('users', UserController::class);
});

// API Routes for external integrations (if needed)
Route::middleware(['auth:sanctum'])->prefix('api/v1')->group(function () {
    // Mobile app endpoints or external integrations
    Route::apiResource('tryouts', TryoutController::class);
    Route::apiResource('materials', MaterialController::class);
});
*/

// Include default auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';