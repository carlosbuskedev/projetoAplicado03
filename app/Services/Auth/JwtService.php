<?php

namespace App\Services\Auth;

use CodeIgniter\HTTP\RequestInterface;
use Config\Auth;
use Exception;

class JwtService
{
    private Auth $config;

    public function __construct(?Auth $config = null)
    {
        $this->config = $config ?? config('Auth');
    }

    public function generate(array $user): string
    {
        $now = time();

        $payload = [
            'iss'   => base_url(),
            'iat'   => $now,
            'exp'   => $now + $this->config->jwtTTL,
            'sub'   => (int) $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        return $this->encode($payload);
    }

    public function decode(string $token): ?object
    {
        try {
            $parts = explode('.', $token);

            if (count($parts) !== 3) {
                return null;
            }

            [$headerB64, $payloadB64, $signatureB64] = $parts;
            $expected = $this->sign("{$headerB64}.{$payloadB64}");

            if (! hash_equals($expected, $signatureB64)) {
                return null;
            }

            $payload = json_decode($this->base64UrlDecode($payloadB64));

            if (! is_object($payload) || ! isset($payload->exp) || $payload->exp < time()) {
                return null;
            }

            return $payload;
        } catch (Exception) {
            return null;
        }
    }

    public function getBearerToken(RequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');

        if ($header === '' || ! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return trim(substr($header, 7));
    }

    private function encode(array $payload): string
    {
        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $body   = $this->base64UrlEncode(json_encode($payload));
        $sig    = $this->sign("{$header}.{$body}");

        return "{$header}.{$body}.{$sig}";
    }

    private function sign(string $data): string
    {
        return $this->base64UrlEncode(
            hash_hmac('sha256', $data, $this->config->jwtSecret, true)
        );
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;

        if ($remainder > 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'), true) ?: '';
    }
}
