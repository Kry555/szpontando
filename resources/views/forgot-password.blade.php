<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/stop_z_wypalaniem_gał.css')

</head>
<body>
    <h2>Reset hasła</h2>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Wyślij link</button>
</form>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif
<br>
    <a href="{{ route('main') }}">
        <button type="button">wróc na strone główną </button>
    </a>
</body>
</html>