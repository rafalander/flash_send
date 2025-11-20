<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" class="favicon" href="{{ asset('images/icons/favicon.png') }}" type="image/png" />
    <title>Flash Send - @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Adicione seus estilos customizados aqui -->
    @yield('styles')
</head>
<body class="bg-light d-flex flex-column" style="min-height: 100vh;">
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
                <div class="card shadow-lg">
                    <!-- Logo ou cabeçalho -->
                    <div class="card-header text-center bg-white border-0 pt-4 mb-0">
                        @yield('header')
                    </div>

                    <!-- Conteúdo principal -->
                    <div class="card-body px-4 py-4">
                        @yield('content')
                    </div>

                    <!-- Rodapé -->
                    <div class="card-footer text-center bg-white border-0 pb-4">
                        @yield('footer')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="p-1 text-bg-light">
        <div class="p-2 text-center" style="font-size: 0.75rem; color: hsl(60, 1%, 41%);">
            &copy; {{ date('Y') }} Flash Send
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
