<?php

namespace App\Http\Requests;

use App\Enums\Lang;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $first_name
 * @property string $username
 * @property string $last_name
 * @property string $birth_date
 * @property string $email
 * @property string $password
 * @property string $device_name
 * @property string $fcm_token
 */
class AuthRequest extends FormRequest
{

    public function messages(): array
    {
        return [
            'email.exists' => __('validation.email_exists'),
            'email.unique' => __('validation.email_unique'),
//            'username.unique' => __('validation.username_unique'),
            'password.min' => __('validation.password_min'),
            'password.confirmed' => __('validation.password_confirmed'),
            'email.required' => __('validation.attribute.required', ['attribute' => ':attribute']),
            'email.email' => __('validation.is_email'),
            'email.max' => __('validation.attribute.max', ['attribute' => ':attribute', 'max' => ':max']),
            'password.required' => __('validation.attribute.required', ['attribute' => ':attribute']),
            'device_name.required' => __('validation.attribute.required', ['attribute' => ':attribute']),
            'language.in' => __('validation.attribute.in', ['attribute' => ':attribute', 'values' => Lang::valuesStr()]),
            'language.max' => __('validation.attribute.max', ['attribute' => ':attribute', 'max' => ':max']),
            'email.regex' => __('validation.attribute.regex', ['attribute' => ':attribute']),
            'password.regex' => __('validation.attribute.regex', ['attribute' => ':attribute']),
//            'username.regex' => __('validation.attribute.regex', ['attribute' => ':attribute']),
        ];
    }


    public function rules(): array
    {
        $route = $this->route()->getName();

        return match ($route) {
            'api.login' => $this->loginRules(),
            'api.register' => $this->registerRules(),
            'api.resetPassword' => $this->resetPasswordRules(),
            default => [],
        };
    }

    private function loginRules(): array
    {
        return [
            'email' => 'required|email|exists:users|max:255',
            'password' => 'required|string|min:8',
            'device_name' => 'required',
            'fcm_token' => 'string|unique:personal_access_tokens,fcm_token',
        ];
    }

    private function registerRules(): array
    {
        return [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|unique:users|max:255|regex:/^[^\p{Cyrillic}]+$/u',
//            'username' => 'string|unique:users|max:255|regex:/^[^\p{Cyrillic}]+$/u',
            'password' => 'required|string|min:8|confirmed|regex:/^[^\p{Cyrillic}]+$/u',
            'language' => 'string|max:2|in:' . Lang::valuesStr(),
            'device_name' => 'required',
            'fcm_token' => 'string|unique:personal_access_tokens,fcm_token',
        ];
    }

    private function resetPasswordRules(): array
    {
        return [
            'email' => 'required|email|exists:users|max:255',
        ];
    }
}
