<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Azka Garden')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50">
    @yield('content')
    
    @yield('scripts')
</body>
</html>