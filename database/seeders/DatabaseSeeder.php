<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('app.initial_admin_name');
        $email = config('app.initial_admin_email');
        $password = config('app.initial_admin_password');

        if (! $name || ! $email || ! $password) {
            throw new RuntimeException(
                'Configure INITIAL_ADMIN_NAME, INITIAL_ADMIN_EMAIL, and INITIAL_ADMIN_PASSWORD before seeding.'
            );
        }

        if (strlen($password) < 12) {
            throw new RuntimeException(
                'The initial administrator password must contain at least 12 characters.'
            );
        }

        $admin = User::where('email', $email)->first();

        if ($admin) {
            if ($admin->role !== 'admin') {
                throw new RuntimeException(
                    'The configured initial administrator email belongs to a non-administrator account.'
                );
            }

            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);
    }
}
