<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CloudflareService
{
    public function verify(?string $token, string $ip): bool
    {
        if (!$token) {
            return false;
        }

        $response = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret'   => config('services.cloudflare.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]
        );

        return $response->json('success') === true;
    }
}
