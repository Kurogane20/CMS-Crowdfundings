<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorCount;
use Carbon\Carbon;

class RecordVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $today = Carbon::today();
        $visitorCount = VisitorCount::where('date', $today)->first();

        if (!$visitorCount) {
            VisitorCount::create(['date' => $today, 'count' => 1]);
        } else {
            $visitorCount->count++;
            $visitorCount->save();
        }

        return $next($request);
    }
}
