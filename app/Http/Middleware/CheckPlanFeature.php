<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $feature = null): Response
    {
        $user = auth()->user();
        
        // Superadmin has access to everything
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }
        
        // Check if user has a plan
        if (!$user || !$user->hasPlan()) {
            return redirect()->route('subscription.plans')
                ->with('error', 'You need to subscribe to a plan to access this feature');
        }
        
        // Check specific feature if provided
        if ($feature) {
            // Handle store limit check
            if ($feature === 'add_store') {
                if (!$user->canAddStore()) {
                    return redirect()->back()
                        ->with('error', 'You have reached your plan\'s store limit. Please upgrade your plan.');
                }
            }
            
            // Handle feature-based access
            if (!$user->hasFeatureAccess($feature)) {
                return redirect()->back()
                    ->with('error', 'This feature is not available in your current plan. Please upgrade.');
            }
        }
        
        return $next($request);
    }
}

