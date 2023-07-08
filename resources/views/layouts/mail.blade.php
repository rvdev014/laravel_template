<html lang="en">
<head>
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    @vite('resources/css/mail.css')
</head>
<body>

<div class="main-bg">
    <h2 class="app-name">{{ config('app.name') }}</h2>
    <div class="container mx-auto py-4">
        @yield('content')
    </div>
</div>

</body>
</html>
