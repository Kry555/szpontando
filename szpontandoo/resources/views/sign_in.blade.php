<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}">
</head>

<body>
    <h1>Sing_in chuju</h1>
    <form method="POST" action="/login">
        @csrf <!--  to chroni przed atakami tylko niewiem jak -->
        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}"> <br>
        <input type="password" name="password" placeholder="Hasło" required>

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
    <a href="{{ route('main') }}">
        <button type="button">wróc na główną jełopie</button>
    </a>

    <a href="{{ route('sign_up') }}">
        <button type="button">Nie masz jeszcze konta zjebie, załuż lepiej bo łysy z brazers wyczysci ci dupe</button>
    </a>
</body>

</html>