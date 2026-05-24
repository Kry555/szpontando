<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    @vite('resources/css/stop_z_wypalaniem_gał.css')
</head>

<body>
    @auth
    <h1>jesteś zalogowany</h1>
    @endauth
    @guest
    <h1>Zaloguj się</h1>
    <form method="POST" action="/login">
        @csrf <!--  to chroni przed atakami tylko niewiem jak -->
        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}"> <br>
        <input type="password" name="password" placeholder="Hasło" required>
        <!-- Komunikaty o nieaktywnym koncie -->
        @if (session('warning'))
        <div style="color:orange; font-weight:bold;">
            {{ session('warning') }}
        </div>
        @endif

        <!-- Komunikaty o banie -->
        @if (session('error'))
        <div style="color:red; font-weight:bold; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
        @endif

        <!-- Błędy -->
        @if($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
        @endif
        <br>
        <!-- Przycisk logowania -->
        <button type="submit">Zaloguj się</button>

    </form>
<a href="{{ route('password.request') }}">
    <button type="button">Zapomniałeś hasła?</button>
</a>

    <a href="{{ route('register.show') }}">
        <button type="button">Utwórz konto</button>
    </a>

    @endguest
    <a href="{{ route('main') }}">
        <button type="button">wróc na strone główną </button>
    </a>

</body>

</html>