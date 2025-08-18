<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTryoutRequest;
use App\Http\Requests\SubmitAnswerRequest;
use App\Services\Contracts\TryoutServiceInterface;
use App\Services\Contracts\QuestionServiceInterface;
use App\Services\Contracts\UserAnswerServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TryoutController extends Controller
{
    public function __construct(
        private TryoutServiceInterface $tryoutService,
        private QuestionServiceInterface $questionService,
        private UserAnswerServiceInterface $userAnswerService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $tryouts = $this->tryoutService->getPublishedTryouts();
        
        return view('tryout.tryout-landing-page', compact('tryouts'));
    }

    public function show(Request $request): View
    {
        $mode = $request->get('mode', 'tryout');
        
        // For now, return the static view with JavaScript handling
        // In real implementation, you'd pass tryout data here
        return view('tryout.tryout-page', compact('mode'));
    }

    public function start(StartTryoutRequest $request): JsonResponse
    {
        try {
            $result = $this->tryoutService->startTryout(
                $request->validated('tryout_id'),
                auth()->id(),
                $request->validated('mode', 'tryout')
            );

            $questions = $this->questionService->getQuestionsForTryout(
                $request->validated('tryout_id'),
                true // randomize
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'session_id' => $result->hasil_id,
                    'questions' => $questions->map(function ($question) {
                        return [
                            'id' => $question->soal_id,
                            'text' => $question->isi_soal,
                            'image' => $question->image_path,
                            'choices' => $question->answerChoices->map(function ($choice) {
                                return [
                                    'id' => $choice->pilihan_id,
                                    'text' => $choice->isi_pilihan,
                                ];
                            }),
                        ];
                    }),
                    'duration' => $result->tryout->durasi_menit,
                    'remaining_time' => $result->getRemainingTime(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start tryout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveAnswer(SubmitAnswerRequest $request): JsonResponse
    {
        try {
            $this->tryoutService->saveAnswer(
                $request->validated('session_id'),
                $request->validated('question_id'),
                $request->validated('choice_id')
            );

            return response()->json([
                'success' => true,
                'message' => 'Answer saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save answer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submit(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->validate(['session_id' => 'required|integer'])['session_id'];
            
            $result = $this->tryoutService->submitTryout($sessionId);

            return response()->json([
                'success' => true,
                'data' => [
                    'score' => $result->skor_akhir,
                    'completed_at' => $result->waktu_selesai,
                    'redirect_url' => route('tryout-completed', ['result' => $result->hasil_id])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit tryout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function result(int $resultId): View
    {
        $result = $this->tryoutService->getTryoutSession($resultId);
        
        if (!$result || $result->user_id !== auth()->id()) {
            abort(403);
        }

        $userAnswers = $this->userAnswerService->getUserAnswers($resultId);
        $statistics = $this->userAnswerService->getAnswerStatistics($resultId);

        return view('tryout.tryout-completed-page', compact('result', 'userAnswers', 'statistics'));
    }

    public function getSession(int $sessionId): JsonResponse
    {
        try {
            $session = $this->tryoutService->getTryoutSession($sessionId);
            
            if (!$session || $session->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            $userAnswers = $this->userAnswerService->getUserAnswers($sessionId);
            $answers = [];
            
            foreach ($userAnswers as $answer) {
                if ($answer->pilihan_id) {
                    $answers[$answer->soal_id] = $answer->pilihan_id;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'session' => $session,
                    'answers' => $answers,
                    'remaining_time' => $session->getRemainingTime(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get session: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history(): View
    {
        $history = $this->tryoutService->getUserTryoutHistory(auth()->id(), 20);
        
        return view('tryout.history', compact('history'));
    }
}