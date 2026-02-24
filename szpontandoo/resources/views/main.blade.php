<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}"> -->
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    <!-- to jest do css tylko pamiętaj npm tun dev -->
</head>

<body>
    <h1>szpontando</h1>
    <h2>tutaj poszponcisz sobie i jeszcze zarobisz</h2>
    @auth
    <p>Witaj, {{ auth()->user()->nick }}!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Wyloguj się</button>
    </form>
    @else
    <p>Loguj sie pało a nie jestes taki incognito fapper <a href="{{ route('login') }}">Zatenteguj się</a> lub <a href="{{ route('register.show') }}">Zajeb konto</a>.</p>
    @endauth


    @foreach($oferty_przeglandarka as $oferta)
    <div class="oferty_przeglandarka">

        <h3>{{ $oferta->typ }}</h3>

        <p><strong>Opis:</strong> {{ $oferta->opis }}</p>
        <p><strong>Cena:</strong> {{ $oferta->cena }} zł</p>
        <p><strong>Adres:</strong> {{ $oferta->adres }}</p>
        <p><strong>Ważne do:</strong> {{ $oferta->do_kiedy_wazne }}</p>

        <hr>

        <p>
            <strong>Autor:</strong>
            {{ $oferta->imie }} {{ $oferta->nazwisko }}
        </p>

        <img src="{{ asset('storage/'.$oferta->profilowe) }}" width="60">

    </div>
    @endforeach




    <!-- <a href="{{ route('login') }}">
        <button type="button">Sign_in</button>
    </a>
    <a href="{{ route('logoutt') }}">
        <button type="button">logout</button>
    </a> -->
</body>

</html>