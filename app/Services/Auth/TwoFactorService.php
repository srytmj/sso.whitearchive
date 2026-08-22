<?php

namespace App\Services\Auth;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    private const RECOVERY_CODE_COUNT = 8;

    private readonly Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function qrCodeSvg(User $user, string $secret): string
    {
        $url = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret,
        );

        $renderer = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());

        return (new Writer($renderer))->writeString($url);
    }

    public function verify(string $secret, string $code): bool
    {
        return (bool) $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * @return string[] plain-text codes shown once to the user
     */
    public function generateRecoveryCodes(): array
    {
        return collect(range(1, self::RECOVERY_CODE_COUNT))
            ->map(fn () => Str::random(10))
            ->all();
    }

    public function hashRecoveryCodes(array $codes): string
    {
        return json_encode(array_map(fn (string $code) => Hash::make($code), $codes));
    }

    public function verifyAndConsumeRecoveryCode(User $user, string $code): bool
    {
        $hashed = json_decode($user->two_factor_recovery_codes ?? '[]', true);

        foreach ($hashed as $index => $hash) {
            if (Hash::check($code, $hash)) {
                unset($hashed[$index]);
                $user->update(['two_factor_recovery_codes' => json_encode(array_values($hashed))]);

                return true;
            }
        }

        return false;
    }
}
