<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}">
</head>

<body>
    <h1>zrub se konto</h1>
    @if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="text" name="nick" placeholder="Nick" value="{{ old('nick') }}" required>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <input type="password" name="password_confirmation" placeholder="Powtórz hasło" required>
        <button type="submit">Zarejestruj się</button>
    </form>
    <a href="{{ route('login') }}">
        <button type="button">Sign_in</button>
    </a>
</body>

</html>