<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Computação Gráfica @yield('title')</title>

        <!-- TensorFlow.js e Media pipe -->
        <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.18.0/dist/tf.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.min.js"></script>
        

        <!-- Bootstrap Bundle com Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Scripts personalizados -->
        @stack('scripts')
        
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" >
        
        <!-- Estilos personalizados -->
        @stack('styles')
    </head>
    <body>
        @include('partials.navbar')
        
        <main class="py-4 main-page-content">
            @yield('content')
        </main>

        <footer class="bg-dark text-white text-center py-3 mt-4">
            <div class="container">
                <p>&copy; {{ date('Y') }} Pex - Drummonetes</p>
            </div>
        </footer>
    </body>
</html>