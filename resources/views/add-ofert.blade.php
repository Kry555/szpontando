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
        <p>Rodzaj sprzątania (wybierz jeden):</p>

        <p><strong>Podstawowe:</strong></p>

        <label>
            <input type="radio" name="typ" value="samochód" {{ old('typ') == 'samochod' ? 'checked' : '' }}> Samochód
        </label><br>

        <label>
            <input type="radio" name="typ" value="rower" {{ old('typ') == 'rower' ? 'checked' : '' }}> Rower
        </label><br>

        <label>
            <input type="radio" name="typ" value="cały_dom" {{ old('typ') == 'caly_dom' ? 'checked' : '' }}> Cały dom
        </label><br>

        <label>
            <input type="radio" name="typ" value="wybrane_pomieszczenia" {{ old('typ') == 'wybrane_pomieszczenia' ? 'checked' : '' }}> Wybrane pomieszczenia
        </label><br>

        <p><strong>Specjalistyczne:</strong></p>

        <label>
            <input type="radio" name="typ" value="brud_ciężki_przemysłowy" {{ old('typ') == 'brud_ciezki_przemyslowy' ? 'checked' : '' }}> Brud ciężki (przemysłowy)
        </label><br>

        <label>
            <input type="radio" name="typ" value="miejsce_zbrodni" {{ old('typ') == 'miejsce_zbrodni' ? 'checked' : '' }}> Miejsce zbrodni
        </label><br>

        <label>
            <input type="radio" name="typ" value="po_remoncie" {{ old('typ') == 'po_remoncie' ? 'checked' : '' }}> Po remoncie
        </label><br>

        <label>
            <input type="radio" name="typ" value="po_imprezie" {{ old('typ') == 'po_imprezie' ? 'checked' : '' }}> Po imprezie
        </label><br>

        <p><strong>Zwierzęta:</strong></p>

        <label>
            <input type="radio" name="typ" value="zwierzęce_zabrudzenia" {{ old('typ') == 'zwierzece_zabrudzenia' ? 'checked' : '' }}> Zwierzęce zabrudzenia
        </label><br>

        <label>
            <input type="radio" name="typ" value="sprzątanie_po_psie" {{ old('typ') == 'sprzatanie_po_psie' ? 'checked' : '' }}> Sprzątanie po psie
        </label><br>

        <label>
            <input type="radio" name="typ" value="kuweta_kota" {{ old('typ') == 'kuweta_kota' ? 'checked' : '' }}> Kuweta kota
        </label><br>

        <p><strong>Inne przydatne:</strong></p>

        <label>
            <input type="radio" name="typ" value="mycie_okien" {{ old('typ') == 'mycie_okien' ? 'checked' : '' }}> Mycie okien
        </label><br>

        <label>
            <input type="radio" name="typ" value="garaż_piwnica" {{ old('typ') == 'garaz_piwnica' ? 'checked' : '' }}> Garaż / piwnica
        </label><br>

        <label>
            <input type="radio" name="typ" value="ogród_tarasy" {{ old('typ') == 'ogrod_tarasy' ? 'checked' : '' }}> Ogród / tarasy
        </label><br>

        <label>
            <input type="radio" name="typ" value="dezynfekcja" {{ old('typ') == 'dezynfekcja' ? 'checked' : '' }}> Dezynfekcja
        </label><br>
        <!-- wolin kazal checkboxy -->
        <!-- <input type="text" name="typ" placeholder="typ" value="{{ old('typ') }}" required><br> -->
        <input type="text" name="adres" placeholder="adres" value="{{ old('adres') }}" required><br>
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
    <h1>Nie jestes zalogowany</h1>
    @endguest
    <a href="{{ route('main') }}">
        <button type="button">powrót na strone główną</button>
    </a>
</body>

</html>