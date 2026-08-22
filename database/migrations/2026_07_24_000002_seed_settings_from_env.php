<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('settings')->exists()) {
            return;
        }

        $now = now();

        $rows = [
            ['key' => 'mail_driver', 'value' => 'resend'],
            ['key' => 'mail_from_address', 'value' => config('mail.from.address')],
            ['key' => 'mail_from_name', 'value' => config('mail.from.name')],
            ['key' => 'resend_api_key', 'value' => $this->encryptIfPresent(env('RESEND_API_KEY'))],
            ['key' => 'avatar_disk', 'value' => env('FILESYSTEM_DISK', 'local') === 's3' ? 's3' : 'local'],
        ];

        foreach ($rows as $row) {
            if ($row['value'] === null) {
                continue;
            }

            DB::table('settings')->insert([
                'key' => $row['key'],
                'value' => $row['value'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->truncate();
    }

    private function encryptIfPresent(?string $value): ?string
    {
        return $value ? Crypt::encryptString($value) : null;
    }
};
