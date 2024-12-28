<?php

namespace App\Http\Middleware;

use App\Jobs\LogToElastic;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AfterUserRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->status() != 200) {
            // log error
            $data = [
                'type' => 'error',
                'status' => $response->status(),
                'url' => $request->url(),
                'incoming' => json_encode($request->all()),
                'outgoing' => json_encode($response->getContent()),
            ];
            LogToElastic::dispatch($data,'error');
        }

        return $response;
    }
}
