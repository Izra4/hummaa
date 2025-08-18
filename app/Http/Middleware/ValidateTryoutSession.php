<?php 

namespace App\Http\Middleware;

use App\Services\Contracts\TryoutServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTryoutSession
{
    public function __construct(
        private TryoutServiceInterface $tryoutService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->route('sessionId') ?? $request->input('session_id');
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Session ID is required'
            ], 400);
        }

        $session = $this->tryoutService->getTryoutSession($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found'
            ], 404);
        }

        if ($session->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to session'
            ], 403);
        }

        if ($session->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Session already completed'
            ], 400);
        }

        if ($session->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Session has expired'
            ], 400);
        }

        // Add session to request for use in controller
        $request->merge(['tryout_session' => $session]);

        return $next($request);
    }
}