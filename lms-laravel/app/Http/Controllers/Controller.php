<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Ascend LMS API Documentation",
 *     description="Dokumentasi API Lengkap untuk Ascend LMS - Project Secure Programming.",
 *
 *     @OA\Contact(
 *         email="ascendlms.learning@gmail.com",
 *         name="Ascend LMS Support"
 *     ),
 *
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     ),
 *
 *     @OA\ExternalDocumentation(
 *         description="Additional Project Information",
 *         url="https://github.com/your-repository"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Primary API Server (Railway Deployment)"
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 */
abstract class Controller
{
    /**
     * @OA\Get(
     *     path="/api/status",
     *     tags={"System"},
     *     summary="Check API Status",
     *     description="Endpoint untuk mengecek apakah API berjalan dengan baik.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="environment", type="string", example="production"),
     *             @OA\Property(property="app_url", type="string", example="https://determined-truth-production-fd42.up.railway.app"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function checkStatus()
    {
        return response()->json([
            'status' => 'OK',
            'environment' => config('app.env'),
            'app_url' => config('app.url'),
            'timestamp' => now(),
        ]);
    }
}
