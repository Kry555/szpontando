<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/add-ofert.css')

</head>

<body>
  <header class="main-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>Dodaj ofertę</p>
    </div>

    <div class="header-buttons">
      <a href="{{ route('main') }}">
        <button type="button">Strona główna</button>
      </a>
    </div>

  </header>
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

  <form method="POST" action="{{ route('add_ofert.post') }}" enctype="multipart/form-data">
    @csrf
<p><strong>Rodzaj sprzątania (wybierz jeden):</strong></p>

<div id="add_typ_radio">
  <div class="radio-box">
  <p class="radio-group-title">Podstawowe</p>

    <label>
      <input type="radio" name="typ" value="samochód" {{ old('typ') == 'samochod' ? 'checked' : '' }}> Samochód
    </label>

    <label>
      <input type="radio" name="typ" value="rower" {{ old('typ') == 'rower' ? 'checked' : '' }}> Rower
    </label>

    <label>
      <input type="radio" name="typ" value="cały_dom" {{ old('typ') == 'caly_dom' ? 'checked' : '' }}> Cały dom
    </label>
    <label>
      <input type="radio" name="typ" value="wybrane_pomieszczenia" {{ old('typ') == 'wybrane_pomieszczenia' ? 'checked' : '' }}> Wybrane pomieszczenia
    </label>
    </div>
<div class="radio-box">
    <p class="radio-group-title">Specjalistyczne:</p>

    <label>
      <input type="radio" name="typ" value="brud_ciężki_przemysłowy" {{ old('typ') == 'brud_ciezki_przemyslowy' ? 'checked' : '' }}> Brud ciężki (przemysłowy)
    </label>

    <label>
      <input type="radio" name="typ" value="miejsce_zbrodni" {{ old('typ') == 'miejsce_zbrodni' ? 'checked' : '' }}> Miejsce zbrodni
    </label>

    <label>
      <input type="radio" name="typ" value="po_remoncie" {{ old('typ') == 'po_remoncie' ? 'checked' : '' }}> Po remoncie
    </label>

    <label>
      <input type="radio" name="typ" value="po_imprezie" {{ old('typ') == 'po_imprezie' ? 'checked' : '' }}> Po imprezie
    </label>
    </div>

<div class="radio-box">
    <p class="radio-group-title">Zwierzęta:</p>

    <label>
      <input type="radio" name="typ" value="zwierzęce_zabrudzenia" {{ old('typ') == 'zwierzece_zabrudzenia' ? 'checked' : '' }}> Zwierzęce zabrudzenia
    </label>

    <label>
      <input type="radio" name="typ" value="sprzątanie_po_psie" {{ old('typ') == 'sprzatanie_po_psie' ? 'checked' : '' }}> Sprzątanie po psie
    </label>

    <label>
      <input type="radio" name="typ" value="kuweta_kota" {{ old('typ') == 'kuweta_kota' ? 'checked' : '' }}> Kuweta kota
    </label>
    </div>

<div class="radio-box">
    <p class="radio-group-title">Inne przydatne:</p>

    <label>
      <input type="radio" name="typ" value="mycie_okien" {{ old('typ') == 'mycie_okien' ? 'checked' : '' }}> Mycie okien
    </label>

    <label>
      <input type="radio" name="typ" value="garaż_piwnica" {{ old('typ') == 'garaz_piwnica' ? 'checked' : '' }}> Garaż / piwnica
    </label>

    <label>
      <input type="radio" name="typ" value="ogród_tarasy" {{ old('typ') == 'ogrod_tarasy' ? 'checked' : '' }}> Ogród / tarasy
    </label>

    <label>
      <input type="radio" name="typ" value="dezynfekcja" {{ old('typ') == 'dezynfekcja' ? 'checked' : '' }}> Dezynfekcja
    </label>
     </div>
      </div>
    <!-- wolin kazal checkboxy -->
    <!-- <input type="text" name="typ" placeholder="typ" value="{{ old('typ') }}" required><br> -->
     <p>adres</p>
    <input type="text" name="adres" placeholder="adres" value="{{ old('adres') }}" required><br>
         <p>cena</p>

    <input
      type="number"
      name="cena"
      placeholder="cena"
      value="{{ old('cena') }}"
      min="0"
      step="0.01"
      required><br>
    <p>Data wygaśnięcia:</p>
    <input
      type="datetime-local"
      name="do_kiedy_wazne"
      value="{{ old('do_kiedy_wazne') }}"
      min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
      required><br>
           <p>opis</p>

    <input type="text" name="opis" placeholder="opis" value="{{ old('opis') }}" required><br>

<p>Zdjęcia do ogłoszenia (max 2):</p>

<div class="file-upload">

  <label class="file-btn">
    Wybierz zdjęcie 1
    <input type="file" name="zdjecie_1" accept="image/*" id="img1">
  </label>

  <div class="img-preview" id="preview1"></div>

  <label class="file-btn">
    Wybierz zdjęcie 2
    <input type="file" name="zdjecie_2" accept="image/*" id="img2">
  </label>

  <div class="img-preview" id="preview2"></div>

</div>


    <br>
    <button type="submit">Wystaw ogloszenie</button>
  </form>

  @endauth
  @guest
  <h1>Nie jestes zalogowany</h1>
  @endguest
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = "";

    if (input.files && input.files[0]) {
        const file = input.files[0];

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }
}

document.getElementById("img1").addEventListener("change", function () {
    previewImage(this, "preview1");
});

document.getElementById("img2").addEventListener("change", function () {
    previewImage(this, "preview2");
});
</script>
</body>

</html>