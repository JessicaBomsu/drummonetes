<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piano Virtual com Mãos - Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/virtual_piano.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div id="infoStatus">Carregando...</div>
        <video id="videoFeed"></video> <canvas id="pianoCanvas"></canvas>
    </div>

    <script src="{{ asset('js/piano_lists.js') }}"></script>
    <script src="{{ asset('js/virtual_piano.js') }}"></script> </body>
</html>