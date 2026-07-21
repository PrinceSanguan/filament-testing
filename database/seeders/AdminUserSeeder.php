<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or update) the admin user that can sign in to the Filament panel.
     *
     * Safe to run repeatedly and safe to run in production: it keys off the
     * email address, so re-running only resets the password instead of
     * creating a duplicate.
     *
     * Configure with ADMIN_EMAIL / ADMIN_PASSWORD / ADMIN_NAME. In production a
     * password is required - if none is set a random one is generated and
     * printed once, rather than falling back to a guessable default.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD');
        $generated = false;

        if (blank($password)) {
            if (app()->isProduction()) {
                $password = Str::password(16);
                $generated = true;
            } else {
                $password = 'password';
            }
        }

        $user = User::firstOrNew(['email' => $email]);

        // email_verified_at is not mass assignable, so set the attributes directly.
        $user->name = env('ADMIN_NAME', 'Admin');
        $user->password = $password; // hashed by the model's 'hashed' cast
        $user->email_verified_at ??= now();
        $user->save();

        $this->command?->info("Admin user ready: {$email}");

        if ($generated) {
            $this->command?->warn("Generated password (shown once): {$password}");
            $this->command?->warn('Set ADMIN_PASSWORD to control this yourself.');
        }
    }
}
