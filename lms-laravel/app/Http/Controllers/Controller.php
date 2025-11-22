<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Ascend LMS API Documentation",
 * description="Dokumentasi API Lengkap untuk Ascend LMS - Project Secure Programming.",
 *
 * @OA\Contact(
 * email="admin@ascendlms.com"
 * ),
 *
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="API Server"
 * )
 */
abstract class Controller
{
    /**
     * @OA\Get(
     * path="/api/status",
     * tags={"System"},
     * summary="Check API Status",
     * description="Endpoint untuk mengecek apakah API berjalan dengan baik.",
     *
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(property="status", type="string", example="OK"),
     * @OA\Property(property="timestamp", type="string", format="date-time")
     * )
     * )
     * )
     */
    public function checkStatus()
    {
        return response()->json(['status' => 'OK', 'timestamp' => now()]);
    }
}
