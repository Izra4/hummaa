<?php

use App\Http\Controllers\ProfileController;
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
    return view('home.welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

Route::get('/bank-soal', function () {
    return view('bank-soal.bank-soal-page');
})->name('bank-soal');

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
})->name('materi.show');

Route::get('/forum', function () {
    return view('forum.page');
})->name('forum');


require __DIR__.'/auth.php';
