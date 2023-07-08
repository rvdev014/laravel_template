<html lang="en">
<head>
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body>

<div class="container mx-auto py-4">
    @yield('content')
</div>
</body>
</html>
