<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
    @vite('resources/css/stop_z_wypalaniem_gał.css')
</head>
<body>
    Email został zmieniony pomyślnie!
<a href="{{ route('main') }}">
    <button>Przejdź na stronę główną</button>
</a>
</body>
</html>