<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defesa Civil - @yield('title', 'Sistema Interno')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .navbar-brand { font-weight: bold; }
        [v-cloak] { display: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-hands-holding-child me-2"></i> Defesa Civil | Loja Solidária Veste Amor</a>
        </div>
    </nav>

    <main class="container mb-5">
        @yield('content')
    </main>

    <script src="{{ mix('js/app.js') }}"></script>
    @stack('final-scripts')
</body>
</html>
