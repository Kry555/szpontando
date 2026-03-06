<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}"> -->
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/add-ofert.css')

</head>

<body>
    @auth
    <h1>Dodaj oferte</h1>
    <!-- wyswietla errory -->
    @if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('add_ofert.post') }}">
        @csrf
        <input type="text" name="adres" placeholder="adres" value="{{ old('adres') }}" required><br>
        <input type="text" name="typ" placeholder="typ" value="{{ old('typ') }}" required><br>
        <input
            type="number"
            name="cena"
            placeholder="cena"
            value="{{ old('cena') }}"
            min="0"
            step="0.01"
            required> <span>zł</span><br>
        <input
            type="datetime-local"
            name="do_kiedy_wazne"
            value="{{ old('do_kiedy_wazne') }}"
            min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
            required><br>
        <input type="text" name="opis" placeholder="opis" value="{{ old('opis') }}" required><br>

        <button type="submit">Wystaw ogloszenie</button>
    </form>

    @endauth
    @guest
    <h1>Nie jestes zalogoany</h1>
    @endguest
    <a href="{{ route('main') }}">
        <button type="button">powrut na strone glowna</button>
    </a>
</body>

</html>