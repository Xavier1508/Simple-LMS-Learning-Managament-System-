<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! method_exists($response, 'headers')) {
            return $response;
        }
        // Memaksa browser menggunakan HTTPS.
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Matikan fitur hardware yang tidak perlu untuk mengurangi attack surface
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=()');

        // Cross-Origin-Opener-Policy (COOP): Mengisolasi browsing context window ini
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        // Cross-Origin-Resource-Policy (CORP): Mencegah resource kita di-load sembarangan
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        // Mencegah browser menyimpan halaman sensitif (HTML/JSON) di cache disk/memory.
        // Kita kecualikan file biner (gambar/pdf) agar tidak lemot.
        $contentType = $response->headers->get('Content-Type');
        if ($contentType && (str_contains($contentType, 'text/html') || str_contains($contentType, 'application/json'))) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        // Hapus header yang membocorkan versi server/PHP
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
