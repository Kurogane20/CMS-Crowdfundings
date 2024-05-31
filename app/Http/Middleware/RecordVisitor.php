<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\VisitorCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class RecordVisitor
{
    public function handle($request, Closure $next)
    {
        // Check if visitor has already been counted in this session
        if (!Session::has('visitor_counted')) {
            $today = Carbon::today();
            $visitorCount = VisitorCount::where('date', $today)->first();

            if (!$visitorCount) {
                VisitorCount::create(['date' => $today, 'count' => 1]);
            } else {
                $visitorCount->count++;
                $visitorCount->save();
            }

            // Mark visitor as counted in this session
            Session::put('visitor_counted', true);
        }

        return $next($request);
    }
}
