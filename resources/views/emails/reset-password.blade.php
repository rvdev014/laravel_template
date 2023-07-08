@extends('layouts.mail')

@section('title', __('mail.reset-password.subject'))

@section('content')

    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-xl">
        <h2 class="mail-title">
            {{ __('mail.reset-password.title', ['name' => $user->getFullName()]) }}
        </h2>

        <div class="mail-content">
            <p class="mail-text">{{ __('mail.reset-password.text') }}</p>
            <br>
            <p class="mail-text">{{ __('mail.reset-password.ur_password') }} <b style="color: #000">{{ $newPassword }}</b></p>
        </div>

    </div>

@endsection
