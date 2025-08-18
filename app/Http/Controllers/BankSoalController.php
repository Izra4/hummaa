<?php 

namespace App\Http\Controllers;

use App\Services\Contracts\TryoutServiceInterface;
use App\Services\Contracts\MaterialServiceInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankSoalController extends Controller
{
    public function __construct(
        private TryoutServiceInterface $tryoutService,
        private MaterialServiceInterface $materialService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        // Get tryouts by type for the bank soal page
        $tpaTryouts = $this->tryoutService->getTryoutsByType('TPA');
        $tiuTryouts = $this->tryoutService->getTryoutsByType('TIU');
        $tkdTryouts = $this->tryoutService->getTryoutsByType('TKD');

        return view('bank-soal.bank-soal-page', compact('tpaTryouts', 'tiuTryouts', 'tkdTryouts'));
    }

    public function getTryoutsByType(string $type)
    {
        $tryouts = $this->tryoutService->getTryoutsByType(strtoupper($type));
        
        return response()->json([
            'success' => true,
            'data' => $tryouts->map(function ($tryout) {
                return [
                    'id' => $tryout->tryout_id,
                    'name' => $tryout->nama_tryout,
                    'description' => $tryout->deskripsi,
                    'question_count' => $tryout->questions_count ?? 0,
                    'duration' => $tryout->durasi_menit,
                ];
            })
        ]);
    }
}
