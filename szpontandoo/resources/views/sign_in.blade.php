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
        <input type="password" name="haslo" placeholder="Hasło" required>

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
</body>

</html>