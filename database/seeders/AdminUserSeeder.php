<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Create/update the single admin login user from env values.
     * Set ADMIN_EMAIL + ADMIN_PASSWORD in .env, then:
     *   php artisan db:seed --class=AdminUserSeeder --force
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command->warn('AdminUserSeeder skipped — set ADMIN_EMAIL and ADMIN_PASSWORD in .env.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => Hash::make($password),
            ],
        );

        $this->command->info("Admin user ready: {$email}");
    }
}
