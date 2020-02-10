<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Lazy Trader</title>

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    </head>
    <body>
        <noscript>
            <strong>We're sorry but lazy trader doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
        </noscript>
        @yield('content')

        <div id="loading-bg">
            <div class="loading-overlay">
                <div class="loading fa-3x">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </body>

    <script src="{{ mix('js/app.js') }}"></script>
</html>
