<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyHmac
{
    /**
     * Проверка HMAC
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('app.hmac_secret');
        $signature = $request->header('X-Signature');

        if (!$signature) {
            return response('X-Signature is missing', 403);
        }

        $calculatedSignature = hash_hmac('sha256',  $request->getContent(), $secret);

        if (!hash_equals($calculatedSignature, $signature)) {
            return response('Invalid signature', 403);
        }

        return $next($request);
    }
}
