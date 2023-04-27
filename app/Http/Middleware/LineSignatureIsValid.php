<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LineSignatureIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 前処理
        $channelSecret = env('LINE_CHANNEL_SECRET'); // Channel secret string
        $httpRequestBody = $request->getContent(); // Request body string
        $hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
        $signature = base64_encode($hash);
        if($request->header('x-line-signature') !== $signature){
            //error
            return response()->json([
                'message'=> 'invalid request',
                ],403);
        }
        // Compare x-line-signature request header string and the signature
        return $next($request);

    }
}
