<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or update) the admin user that can sign in to the Filament panel.
     *
     * Safe to run repeatedly: it keys off the email address, so re-running only
     * resets the password instead of creating a duplicate.
     *
     * Defaults to admin@example.com / password in every environment. Override
     * with ADMIN_EMAIL / ADMIN_PASSWORD / ADMIN_NAME if you ever want to.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');

        $user = User::firstOrNew(['email' => $email]);

        // email_verified_at is not mass assignable, so set the attributes directly.
        $user->name = env('ADMIN_NAME', 'Admin');
        $user->password = env('ADMIN_PASSWORD', 'password'); // hashed by the model's 'hashed' cast
        $user->email_verified_at ??= now();
        $user->save();

        $this->command?->info("Admin user ready: {$email} / password");
    }
}
