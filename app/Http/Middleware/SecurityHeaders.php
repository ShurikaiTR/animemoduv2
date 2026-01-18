<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Content-Type-Options: Tarayıcının MIME type sniffing yapmasını engeller
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options: Clickjacking saldırılarına karşı koruma
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection: Eski tarayıcılarda XSS koruması
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Referer bilgisinin nasıl paylaşılacağını kontrol eder
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Tarayıcı özelliklerini kısıtlar
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
