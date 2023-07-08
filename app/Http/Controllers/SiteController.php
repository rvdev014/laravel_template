<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function verifyEmail($userId, Request $request): JsonResponse|RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->to('/')->with('error', 'Invalid verification link!');
        }

        $user = User::findOrFail($userId);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/')->with('success', 'Email verified!');
    }

    public function resetPassword(Request $request): View
    {
        return view('reset-password-form', [
            'token' => $request->route('token'),
            'email' => $request->get('email'),
        ]);
    }

    public function resetPasswordSubmit(Request $request): string
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password_hash' => Hash::make($password)
                ]);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return redirect()
            ->route('password.reset', ['token' => $request->get('token')])
            ->with(
                'success',
                $status == Password::PASSWORD_RESET
                    ? 'Password has been reset!'
                    : 'Error resetting password!'
            );
    }
}
