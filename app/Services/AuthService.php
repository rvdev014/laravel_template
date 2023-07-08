<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AuthService
{
    public function getUser(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function createToken(User $user, string $device_name, string $fcm_token = null): string
    {
        return $user
            ->createToken($device_name, $fcm_token)
            ->plainTextToken;
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    /**
     * @throws Throwable
     */
    public function registerUser(array $data): User
    {
        try {
            DB::beginTransaction();

            $user = $this->createUser([
                ...$data,
                'password_hash' => Hash::make($data['password'])
            ]);
            $user->sendEmailVerificationNotification();

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function resetPassword(string $email): void
    {
        try {
            $user = $this->getUser($email);
            $newPassword = Str::random(8);

            DB::beginTransaction();

            $user->updateOrFail(['password_hash' => Hash::make($newPassword)]);
            $this->sendResetPasswordEmail($user, $newPassword);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function sendResetPasswordEmail(User $user, string $newPassword): void
    {
        $mailable = new ResetPasswordMail($user, $newPassword);
        Mail::to($user->email)->queue($mailable);
    }

    public function logout(User $user): bool
    {
        return $user->currentAccessToken()->delete();
    }

    public function logoutAll(User $user): bool
    {
        return $user->tokens()->delete();
    }

}
