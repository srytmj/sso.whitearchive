<?php

namespace App\Services\Dashboard;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class SettingsService
{
    private const SECRET_KEYS = ['resend_api_key', 'smtp_password', 's3_secret'];

    public function all(): array
    {
        $keys = [
            'mail_driver', 'mail_from_address', 'mail_from_name', 'resend_api_key',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
            'avatar_disk', 's3_key', 's3_secret', 's3_region', 's3_bucket', 's3_endpoint',
        ];

        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key);
        }

        return $values;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key, $default);

        if ($value !== null && in_array($key, self::SECRET_KEYS, true)) {
            return Crypt::decryptString($value);
        }

        return $value;
    }

    public function saveMail(array $data): void
    {
        Setting::set('mail_driver', $data['mail_driver']);
        Setting::set('mail_from_address', $data['mail_from_address']);
        Setting::set('mail_from_name', $data['mail_from_name']);

        if ($data['mail_driver'] === 'resend') {
            $this->setSecret('resend_api_key', $data['resend_api_key'] ?? null);
        } else {
            Setting::set('smtp_host', $data['smtp_host'] ?? null);
            Setting::set('smtp_port', $data['smtp_port'] ?? null);
            Setting::set('smtp_username', $data['smtp_username'] ?? null);
            Setting::set('smtp_encryption', $data['smtp_encryption'] ?? null);
            $this->setSecret('smtp_password', $data['smtp_password'] ?? null);
        }
    }

    public function saveAvatarStorage(array $data): void
    {
        Setting::set('avatar_disk', $data['avatar_disk']);

        if ($data['avatar_disk'] === 's3') {
            Setting::set('s3_key', $data['s3_key'] ?? null);
            Setting::set('s3_region', $data['s3_region'] ?? null);
            Setting::set('s3_bucket', $data['s3_bucket'] ?? null);
            Setting::set('s3_endpoint', $data['s3_endpoint'] ?? null);
            $this->setSecret('s3_secret', $data['s3_secret'] ?? null);
        }
    }

    public function avatarDisk(): string
    {
        return $this->get('avatar_disk', 'local');
    }

    /**
     * Apply DB-stored settings onto the runtime config, so every existing
     * Mail::/Notification::/Storage:: call automatically picks them up.
     */
    public function applyToRuntimeConfig(): void
    {
        $driver = $this->get('mail_driver', 'resend');

        config(['mail.default' => $driver]);
        config(['mail.from' => [
            'address' => $this->get('mail_from_address', config('mail.from.address')),
            'name' => $this->get('mail_from_name', config('mail.from.name')),
        ]]);

        if ($driver === 'resend') {
            $key = $this->get('resend_api_key');
            if ($key) {
                config(['services.resend.key' => $key]);
            }
        } else {
            config(['mail.mailers.smtp' => array_merge(config('mail.mailers.smtp', []), [
                'host' => $this->get('smtp_host'),
                'port' => $this->get('smtp_port'),
                'username' => $this->get('smtp_username'),
                'password' => $this->get('smtp_password'),
                'encryption' => $this->get('smtp_encryption'),
            ])]);
        }

        if ($this->avatarDisk() === 's3') {
            config(['filesystems.disks.s3' => array_merge(config('filesystems.disks.s3', []), [
                'key' => $this->get('s3_key'),
                'secret' => $this->get('s3_secret'),
                'region' => $this->get('s3_region'),
                'bucket' => $this->get('s3_bucket'),
                'endpoint' => $this->get('s3_endpoint'),
            ])]);
        }
    }

    private function setSecret(string $key, ?string $value): void
    {
        Setting::set($key, $value ? Crypt::encryptString($value) : null);
    }
}
