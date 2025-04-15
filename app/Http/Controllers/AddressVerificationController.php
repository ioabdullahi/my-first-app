<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AddressVerificationController extends Controller
{
    /**
     * Generate QoreID access token
     */
    private function generateQoreIDToken()
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'text/plain',
                'content-type' => 'application/json',
            ])->post(config('services.qoreid.token_url'), [
                'clientId' => config('services.qoreid.client_id'),
                'secret' => config('services.qoreid.client_secret'),
            ]);

            if ($response->successful()) {
                return $response->json()['accessToken'];
            }

            Log::error('QoreID Token Generation Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('Failed to generate QoreID token');

        } catch (\Exception $e) {
            Log::error('QoreID Token Generation Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify address
     */
    public function verify(AddressVerificationRequest $request)
    {
        // Validate the incoming request
        $validated = $request->validate();

        try {
            // Generate fresh token for each request
            $accessToken = $this->generateQoreIDToken();

            $headers = [
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $accessToken,
                'content-type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post(config('services.qoreid.address_verify_url'), $validated);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('QoreID Address Verification Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return response()->json([
                'error' => 'Address verification failed',
                'details' => $response->json(),
                'qoreid_status' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Address Verification Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}