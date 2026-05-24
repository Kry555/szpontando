<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Nowy email</title>
@vite('resources/css/stop_z_wypalaniem_gał.css')
</head>
<body>

<h1>Podaj nowy email</h1>

<form method="POST" action="{{ route('email.change.send.new') }}">
    @csrf

    <input type="hidden"
           name="request_id"
           value="{{ $request_id }}">

    <input type="email"
           name="new_email"
           placeholder="nowy email"
           required>

    <button type="submit">
        Wyślij potwierdzenie
    </button>
</form>
@if(session('status'))
    <p>
        {{ session('status') }}
    </p>
@endif

</body>
</html>