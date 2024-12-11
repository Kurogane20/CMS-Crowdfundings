<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\PageView;

class RecordPageView
{
    public function handle($request, Closure $next)
    {
        $url = $request->url();
        $pageView = PageView::where('url', $url)->first();

        if (!$pageView) {
            PageView::create(['url' => $url, 'count' => 1]);
        } else {
            $pageView->count++;
            $pageView->save();
        }

        return $next($request);
    }
}
