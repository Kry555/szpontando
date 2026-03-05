<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
    @vite('resources/css/stop_z_wypalaniem_gał.css')
</head>

<body>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Wyloguj</button>
    </form>
</body>

</html>