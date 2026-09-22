<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    public function login(array $credentials): ?array
    {
        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            return null;
        }

        if (!Hash::check($credentials['password'], $admin->password)) {
            return null;
        }

        $token = $admin
            ->createToken('admin-token')
            ->plainTextToken;

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout(Admin $admin): void
    {
        $token = $admin->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }
}