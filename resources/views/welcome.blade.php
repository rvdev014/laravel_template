@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

    @if(session('success'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
            <div class="flex">
                <p class="font-bold">Your email has been verified!</p>
            </div>
        </div>
        <br>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                <div class="flex">
                    <p class="font-bold">$error</p>
                </div>
            </div>
            <br>
        @endforeach
    @endif

    <h1 class="text-3xl text-center font-bold">
        Welcome, guest!
    </h1>
@endsection
