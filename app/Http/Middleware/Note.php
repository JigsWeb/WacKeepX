<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Note
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $note = $request->route('note');

        if($note->user_id !== Auth::user()->id) return response()->json(['error' => 'Cette note ne vous appartient pas'], 401);

        return $next($request);
    }
}
