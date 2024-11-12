<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Панель управления</title>
</head>
<body>

<div class="login">
    <form action="{{ route('authenticate') }}" method="post">
        @csrf

        <label>
            <p>E-mail</p>
            <input type="text" name="email" style="margin-bottom: 16px;">
        </label>

        <label>
            <p>Пароль</p>
            <input type="password" name="password">
        </label>

        @if(isset ($errors) && count($errors) > 0)
            <div class="alert" role="alert">
                <ul class="list-unstyled mb-0">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Войти</button>
    </form>
</div>

</body>
</html>
