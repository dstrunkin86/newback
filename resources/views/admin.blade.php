<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
        <title>Панель управления</title>
        <script>
            @auth
              window.UserRoles = {!! json_encode($userRole, true) !!};
            @else
              window.UserRoles = [];
            @endauth
        </script>

    </head>
    <body>
        <div id="app">
            <App></App>
        </div>
        <script src="{{ mix('/admin/js/app.js') }}"></script>
    </body>
</html>
