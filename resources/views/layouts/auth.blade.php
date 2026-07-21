<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SSO Engine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-zinc-900">whitearchive.id</h1>
            <p class="text-sm text-zinc-500 mt-1">@yield('subtitle')</p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-2xl px-6 py-8 sm:px-8 sm:py-10 shadow-sm">
            @yield('content')
        </div>
    </div>
</body>
</html>
