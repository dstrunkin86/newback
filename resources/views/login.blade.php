<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Панель управления</title>
    <style>
        .login {
            width: 400px;
            margin: 5% auto;
        }

        .login form {
            border: 1px solid #000;
            padding: 20px;
        }

        .login label p {
            color: #000000;
            padding: 0;
            margin: 0px;
        }

        .login input {
            width: 94.5%;
            height: 40px;
            border: 1px solid #000000;
            font-size: 18px;
            padding: 0 10px;
            color: #000000;
        }

        .login input:focus {
            border: 1px solid #000000;
            background: none;
        }

        .login button {
            height: 40px;
            width: 100%;
            margin: 30px 0 0 0;
            display: block;
            border: 1px solid #010101;
            background: #ffffffeb;
            border-radius: 0px;
            color: #000000;
        }

        .alert {
            color: #f44336c4;
        }

        .alert ul {
            padding-left: 0px;
        }
    </style>
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

            @if (isset($errors) && count($errors) > 0)
                <div class="alert" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
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
