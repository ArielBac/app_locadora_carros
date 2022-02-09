<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Locavel</title>
</head>

<body>
    <div class="">
        @if (Route::has('login'))
            <div class="">
                @auth
                    <a href="{{ url('/home') }}" class="">Home</a>
                @else
                    <a href="{{ route('login') }}" class="">Entrar</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="">Registrar</a>
                    @endif
                @endauth
            </div>
        @endif
</body>

</html>
