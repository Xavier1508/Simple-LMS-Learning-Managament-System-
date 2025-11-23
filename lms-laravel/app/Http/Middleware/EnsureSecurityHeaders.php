<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request):
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (!method_exists($response, 'headers')) {
            return $response;
        }
        // Memaksa browser menggunakan HTTPS selama 1 tahun
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Mencegah browser menebak-nebak tipe file (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mencegah website di-iframe oleh orang lain (Clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Lapisan tambahan untuk mencegah XSS di browser lama
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Menjaga privasi user saat klik link keluar
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Mematikan fitur browser yang tidak dipakai (Kamera, Mic, Lokasi)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=()');

        // Hapus header yang membocorkan info server
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
