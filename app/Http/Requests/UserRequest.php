<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read string $old_password
 * @property-read string $password
 * @property-read string $password_confirmation
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read string $birth_date
 * @property-read string $username
 * @property-read string $email
 * @property-read string $new_email
 * @property-read string $language
 * @property-read string $gender
 * @property-read UploadedFile $avatar
 * @property-read string $remove_avatar
 */
class UserRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'email.exists' => __('validation.email_exists'),
            'email.unique' => __('validation.email_unique'),
            'email.email' => __('validation.is_email'),
            'email.regex' => __('validation.attribute.regex', ['attribute' => ':attribute']),
//            'username.unique' => __('validation.username_unique'),
            'password.min' => __('validation.password_min'),
            'password.confirmed' => __('validation.password_confirmed'),
            'password.required' => __('validation.attribute.required', ['attribute' => ':attribute']),
            'old_password.required' => __('validation.attribute.required', ['attribute' => ':attribute']),
            'birth_date.date' => __('validation.attribute.date', ['attribute' => ':attribute']),
            'gender.in' => __('validation.attribute.in', ['attribute' => ':attribute', 'values' => Gender::valuesStr()]
            ),
            'language.in' => __('validation.attribute.in', ['attribute' => ':attribute', 'values' => Lang::valuesStr()]
            ),
            'language.max' => __('validation.attribute.max', ['attribute' => ':attribute', 'max' => ':max']),
            'avatar.image' => __('validation.attribute.image', ['attribute' => ':attribute']),
            'avatar.size' => __('validation.attribute.size', ['attribute' => ':attribute', 'size' => ':size']),
        ];
    }


    public function rules(): array
    {
        $route = $this->route()->getName();

        return match ($route) {
            'api.user.update' => $this->updateRules(),
            'api.user.changePassword' => $this->changePasswordRules(),
            'api.user.deleteAccount' => $this->deleteAccountRules(),
            default => [],
        };
    }

    private function updateRules(): array
    {
        return [
            'remove_avatar' => 'boolean',

            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'birth_date' => 'date',
//            'username' => 'string|max:255|unique:users,username,' . $this->user()->id,
            'email' => 'email|max:255|unique:users,email,' . $this->user()->id . '|regex:/^[^\p{Cyrillic}]+$/u',
            'language' => 'string|max:2|in:' . Lang::valuesStr(),
            'gender' => 'in:' . Gender::valuesStr(),
            'avatar' => 'nullable|image',
        ];
    }

    private function changePasswordRules(): array
    {
        return [
//            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    private function deleteAccountRules(): array
    {
        return [
            'email' => 'required|email|exists:users|max:255',
            'password' => 'required|string|min:8',
        ];
    }
}
