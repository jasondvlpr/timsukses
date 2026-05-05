<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
    }

    /**
     * Send a WhatsApp message using Fonnte API.
     */
    public function sendMessage($target, $message, $countryCode = '62')
    {
        if (empty($this->token)) {
            Log::error('Fonnte Token is not set in .env');
            return false;
        }

        try {
            // Using asMultipart() to match official cURL example (form-data)
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asMultipart()->post($this->baseUrl . '/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => $countryCode,
                'delay' => '2',
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());
            return false;
        }
    }
}
