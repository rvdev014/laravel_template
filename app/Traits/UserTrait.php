<?php

namespace App\Traits;

use App\Models\PasswordResetTokens;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    public static function findByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public static function findByResetToken(string $token)
    {
        return PasswordResetTokens::where('token', $token)->first();
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password_hash);
    }
}
