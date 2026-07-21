<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RedirectUriRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('Redirect URI tidak valid.');
            return;
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
        $host   = strtolower((string) parse_url($value, PHP_URL_HOST));

        if (in_array($scheme, ['javascript', 'data', 'file'], true)) {
            $fail('Scheme tidak diizinkan.');
            return;
        }

        if (str_contains($value, '#')) {
            $fail('Redirect URI tidak boleh mengandung fragment (#).');
            return;
        }

        $isLocalhost = in_array($host, ['localhost', '127.0.0.1'], true);

        if ($scheme === 'http' && !$isLocalhost) {
            $fail('Wajib menggunakan HTTPS untuk domain publik.');
            return;
        }

        if ($scheme !== 'https' && !$isLocalhost) {
            $fail('Redirect URI tidak valid.');
            return;
        }

        if (!$isLocalhost) {
            $ip = gethostbyname($host);
            foreach (['10.', '172.16.', '172.17.', '172.18.', '172.19.', '172.20.', '172.21.', '172.22.', '172.23.', '172.24.', '172.25.', '172.26.', '172.27.', '172.28.', '172.29.', '172.30.', '172.31.', '192.168.'] as $range) {
                if (str_starts_with($ip, $range)) {
                    $fail('Redirect URI mengarah ke private IP range.');
                    return;
                }
            }
        }
    }
}
