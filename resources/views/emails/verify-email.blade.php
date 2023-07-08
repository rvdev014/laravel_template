@extends('layouts.mail')

@section('title', __('mail.reset-password.subject'))

@section('content')

    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-xl">
        <h2 class="mail-title">
            {{ __('mail.verify-email.title', ['name' => $user->getFullName()]) }}
        </h2>

        <div class="mail-content">
            <p class="mail-text">{{ __('mail.verify-email.text') }}</p>
            <div class="mail-action">
                <a href="{{ $url }}" class="bg-blue-500 text-white py-1.5 px-4 rounded" style="background:#2d3748;">
                    {{ __('mail.verify-email.button') }}
                </a>
            </div>
            <p class="mail-text"> {{ __('mail.verify-email.expire', ['count' => $expireTime]) }} </p>
            <p class="mail-text"> {{ __('mail.verify-email.no-action') }} </p>
            <p class="mail-text" style="margin-bottom: 0"> {{ __('mail.verify-email.regards') }} </p>
            <p class="mail-text"> {{ config('app.name') }} </p>
        </div>

    </div>

@endsection
