<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Check if user is still authenticated
     */
    public function check(Request $request)
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user()->name,
                'expires_at' => now()->addMinutes(config('session.lifetime'))->toIso8601String()
            ]);
        }

        return response()->json([
            'authenticated' => false
        ], 401);
    }

    /**
     * Ping to extend session
     */
    public function ping(Request $request)
    {
        if (Auth::check()) {
            // Just accessing the session extends its lifetime
            $request->session()->put('last_activity', now());
            
            return response()->json([
                'success' => true,
                'message' => 'Session extended',
                'expires_at' => now()->addMinutes(config('session.lifetime'))->toIso8601String()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not authenticated'
        ], 401);
    }
}

