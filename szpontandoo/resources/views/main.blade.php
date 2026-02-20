<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}">
</head>

<body>
    <h1>szpontando</h1>
    <h2>tutaj poszponcisz sobie i jeszcze zarobisz</h2>
    <a href="{{ route('login') }}">
        <button type="button">Sign_in</button>
    </a>
    <a href="{{ route('logoutt') }}">
        <button type="button">logout</button>
    </a>
</body>

</html>