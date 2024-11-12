<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <title>Панель управления</title>
        {{-- <script>
            @auth
              window.Permissions = {!! json_encode($permissions, true) !!};
            @else
              window.Permissions = [];
            @endauth
        </script> --}}

    </head>
    <body>
        <div id="app">
            <App></App>
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
