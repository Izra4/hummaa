<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTryoutMode
{
    public function handle(Request $request, Closure $next, string $allowedMode = null): Response
    {
        $session = $request->get('tryout_session');
        
        if ($allowedMode && $session && $session->mode !== $allowedMode) {
            return response()->json([
                'success' => false,
                'message' => "This action is only allowed in {$allowedMode} mode"
            ], 403);
        }

        return $next($request);
    }
}