<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
    @vite('resources/css/stop_z_wypalaniem_gał.css')
</head>
<body>

<h1>Aktywuj swoje konto</h1>

<p>Sprawdź swoją skrzynkę email i kliknij link aktywacyjny.</p>

<a href="{{ route('main') }}">
    <button>Przejdź na stronę główną</button>
</a>

</body>
</html>