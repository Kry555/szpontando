<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/main.css')
</head>

<body>

  <!-- HEADER -->
  <div class="header">

    <img src="{{ asset('images/logo.png') }}" alt="logo">

    <div class="napisy">
      <h2 style="color:orange">
        tutaj poszponcisz sobie i jeszcze zarobisz
      </h2>
    </div>
    @auth
    <p id="wit">
      Witaj, {{ auth()->user()->nick }}!
    </p>

    <div class="buttony">
      @if(auth()->user()->czy_admin)

      <a href="{{ route('admin.dashboard') }}">
        <button type="button"
          style="background:orange; color:white;">
          Panel Admina
        </button>
      </a>

      @endif
      <a href="{{ route('add_ofert') }}">
        <button type="button">
          Dodaj ofertę
        </button>
      </a>

      <a href="{{ route('set_profil') }}">
        <button type="button">
          Ustaw profil
        </button>
      </a>

      <a href="{{ route('my_ofert') }}">
        <button type="button">
          Moje oferty
        </button>
      </a>

      <a href="{{ route('work_ofert') }}">
        <button type="button">
          Twoje zgłoszenia
        </button>
      </a>

      <button onclick="openModal_Wiadomosci()">
        Wiadomości
        ({{ is_string($notf) ? 0 : $notf->count() }})
      </button>

      <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" style=" background:#dc3545; color:white;">
          Wyloguj się
        </button>
      </form>
    </div>

    @else

    <p class="guest_box">
      Utwórz konto już dziś!

      <a href="{{ route('login') }}">
        Zaloguj się
      </a>

      lub

      <a href="{{ route('register.show') }}">
        Zarejestruj się
      </a>
    </p>
    @endauth
  </div>

  <!-- RANKING -->
  <a href="{{ route('ranking') }}" id="ran_but">
    <button type="button" style="background: gold; color: black; font-weight: bold;">Ranking Wykonawców </button>
  </a>

  <!-- FILTRY -->
  <div class="filtry">
    <form method="GET" action="{{ route('main') }}">
      <label>Cena od:</label>
      <input type="number" name="cena_min"
        value="{{ request('cena_min') }}"
        min="0"
        style="width:60px">

      <label>Cena do:</label>
      <input type="number" name="cena_max"
        value="{{ request('cena_max') }}"
        min="0"
        style="width:60px">
      <label>Miasto:</label>
      <input type="text"
        name="miasto"
        value="{{ request('miasto') }}"
        autocomplete="off"
        style="width:100px">

      <div class="typ_lista">
        <label for="typ">Typ oferty:</label>

        <select name="typ" id="typ">

          <option value="">Wszystkie</option>

          <optgroup label="Podstawowe">

            <option value="samochod"
              {{ request('typ') == 'samochod' ? 'selected' : '' }}>
              Samochód
            </option>

            <option value="rower"
              {{ request('typ') == 'rower' ? 'selected' : '' }}>
              Rower
            </option>

            <option value="caly_dom"
              {{ request('typ') == 'caly_dom' ? 'selected' : '' }}>
              Cały dom
            </option>

            <option value="wybrane_pomieszczenia"
              {{ request('typ') == 'wybrane_pomieszczenia' ? 'selected' : '' }}>
              Wybrane pomieszczenia
            </option>

          </optgroup>

          <optgroup label="Specjalistyczne">

            <option value="brud_ciezki_przemyslowy"
              {{ request('typ') == 'brud_ciezki_przemyslowy' ? 'selected' : '' }}>
              Brud ciężki (przemysłowy)
            </option>

            <option value="miejsce_zbrodni"
              {{ request('typ') == 'miejsce_zbrodni' ? 'selected' : '' }}>
              Miejsce zbrodni
            </option>

            <option value="po_remoncie"
              {{ request('typ') == 'po_remoncie' ? 'selected' : '' }}>
              Po remoncie
            </option>

            <option value="po_imprezie"
              {{ request('typ') == 'po_imprezie' ? 'selected' : '' }}>
              Po imprezie
            </option>

          </optgroup>

          <optgroup label="Zwierzęta">

            <option value="zwierzece_zabrudzenia"
              {{ request('typ') == 'zwierzece_zabrudzenia' ? 'selected' : '' }}>
              Zwierzęce zabrudzenia
            </option>

            <option value="sprzatanie_po_psie"
              {{ request('typ') == 'sprzatanie_po_psie' ? 'selected' : '' }}>
              Sprzątanie po psie
            </option>

            <option value="kuweta_kota"
              {{ request('typ') == 'kuweta_kota' ? 'selected' : '' }}>
              Kuweta kota
            </option>

          </optgroup>

          <optgroup label="Inne przydatne">

            <option value="mycie_okien"
              {{ request('typ') == 'mycie_okien' ? 'selected' : '' }}>
              Mycie okien
            </option>

            <option value="garaz_piwnica"
              {{ request('typ') == 'garaz_piwnica' ? 'selected' : '' }}>
              Garaż / piwnica
            </option>

            <option value="ogrod_tarasy"
              {{ request('typ') == 'ogrod_tarasy' ? 'selected' : '' }}>
              Ogród / tarasy
            </option>

            <option value="dezynfekcja"
              {{ request('typ') == 'dezynfekcja' ? 'selected' : '' }}>
              Dezynfekcja
            </option>

          </optgroup>

        </select>
      </div>
      <div class="status_lista">
        <label for="status">Status:</label>
        <select name="status" id="status">

          <option value="wszystkie"
            {{ request('status', 'aktywna') == 'wszystkie' ? 'selected' : '' }}>
            Wszystkie
          </option>

          <option value="aktywna"
            {{ request('status', 'aktywna') == 'aktywna' ? 'selected' : '' }}>
            Aktywne
          </option>

          <option value="zaakceptowana"
            {{ request('status') == 'zaakceptowana' ? 'selected' : '' }}>
            Zaakceptowane
          </option>

          <option value="anulowane"
            {{ request('status') == 'anulowane' ? 'selected' : '' }}>
            Anulowane
          </option>

          <option value="zbanowana"
            {{ request('status') == 'zbanowana' ? 'selected' : '' }}>
            Zbanowane
          </option>
        </select>
      </div>
      <button type="submit" class="buttony">Filtruj</button>
    </form>

  </div>

  <div class="oferty">
    @foreach($oferty_przeglandarka as $oferta)
    <div class="oferty_przeglandarka">

      <div class="informacje">

        <a href="{{ route('oferta.show', $oferta->id_oferty) }}"
          style="text-decoration:none; color:inherit;">

          <!-- MINIATURY -->
          <div class="zdjecia-miniatury">

            @if($oferta->zdjecie_1)
            <img src="{{ asset('images/oferty/' . $oferta->zdjecie_1) }}" alt="Zdjęcie 1">
            @endif

            @if($oferta->zdjecie_2)
            <img src="{{ asset('images/oferty/' . $oferta->zdjecie_2) }}" alt="Zdjęcie 2">
            @endif
          </div>

          <!-- TYTUŁ -->
          <h3 id="tytul_oferty">
            {{ ucfirst(str_replace('_', ' ', $oferta->typ)) }}
          </h3>

          <!-- OPIS -->
          <p id="opis">
            <strong>Opis:</strong>
            {{ Str::limit($oferta->opis, 100) }}
          </p>

          <!-- CENA -->
          <p id="cena">
            <strong>Cena:</strong>
            {{ $oferta->cena }} zł
          </p>

          <!-- ADRES -->
          <p id="adres">
            <strong>Adres:</strong>
            {{ $oferta->adres }}
          </p>

          <!-- WAŻNOŚĆ -->
          <p id="waznosc">
            <strong>Ważne do:</strong>
            {{ $oferta->do_kiedy_wazne }}
          </p>

          <!-- DATA -->
          <p>
            <strong>Utworzone:</strong>
            {{ $oferta->created_at }}
          </p>

          <!-- STATUS -->
          <p id="status">
            <strong>Status:</strong>
            <span style="color:
              {{ $oferta->status == 'zbanowana'
                ? 'red'
                 : ($oferta->status == 'aktywna'
                    ? 'green'
                    : 'orange') }};">
              {{ $oferta->status }}
            </span>
          </p>
          <hr>
        </a>

        <!-- AUTOR -->
        <p id="autor">
          <strong>Autor:</strong>
          {{ $oferta->imie }}
          {{ $oferta->nazwisko }}
        </p>

      </div>

      <!-- GUZIKI -->
      <div class="buttony_oferty">
        @php
        $zgloszony = in_array(
        $oferta->id_oferty,
        $Zgloszenia_aktywne ?? []
        );
        @endphp
        @auth

        @if($zgloszony)

        <button class="disabled">

          Już zgłoszony

        </button>

        @elseif($oferta->status === 'aktywna')

        <button id="zglos_but" onclick="openModal_zglos({{ $oferta->id_oferty }})">
          Zgłoś się
        </button>

        @endif

        @if(auth()->user()->czy_admin)

        @if($oferta->status !== 'zbanowana')

        <button id="ban_but" onclick="openModal_banuj({{ $oferta->id_oferty }})">
          Zbanuj ofertę
        </button>

        @else

        <form action="{{ route('admin.odbanuj_oferte') }}"
          method="POST">
          @csrf
          <input type="hidden"
            name="id_oferty"
            value="{{ $oferta->id_oferty }}">

          <button id="odbanuj_but" type="submit">
            Odbanuj ofertę
          </button>
        </form>

        @endif

        @else

        @if($oferta->status === 'aktywna')

        <button id="zglos_nad_but" onclick="openModal_naduzycie({{ $oferta->id_oferty }})">
          Zgłoś naruszenie
        </button>

        @endif

        @endif

        @endauth

        @guest

        <button class="disabled">
          Zaloguj się aby zgłosić
        </button>

        @endguest

      </div>

    </div>

    @endforeach

  </div>

  <!-- MODALE -->
  <div class="modale">

    <div id="modal_banuj" class="modal">

      <div class="modal_content">

        <h2>Zbanuj ofertę</h2>

        <form method="POST" action="{{ route('admin.banuj_oferte') }}">
          @csrf

          <input type="hidden" name="id_oferty" id="banuj_oferta_id">

          <label>Powód bana:</label>

          <textarea name="powod" required></textarea>

          <br><br>

          <button type="submit" class="submit_but">
            Zbanuj ofertę
          </button>

          <button type="button" onclick="closeModal_banuj()" class="zamknij_but">
            Anuluj
          </button>
        </form>
      </div>
    </div>

    <div id="modal_zglos" class="modal">
      <div class="modal_content">
        <h2>Wyślij wiadomość</h2>

        <form id="modal_form" method="POST" action="{{ route('oferta.wybierz') }}">
          @csrf
          <input type="hidden" name="oferta_id" id="modal_oferta_id" value="">

          <label>Wiadomość:</label>
          <textarea name="wiadomosc" required></textarea>

          <br><br>
          <button type="submit" class="submit_but">Wyślij</button>
          <button type="button" onclick="closeModal_zglos()" class="zamknij_but">Anuluj</button>
        </form>
      </div>
    </div>

    <div id="modal_Wiadomosci" class="modal">
      <div class="modal_content">
        <h2>Wiadomosci</h2>
        @if(is_string($notf))
        <p>{{ $notf }}</p>
        @else

        @if($notf->isEmpty())
        <p>Brak nowych powiadomień</p>
        @else

        @foreach($notf as $wiadomosc)
        <div class="wiadomosc">
          <strong>{{ $wiadomosc->tytul }}</strong>
          <p>{{ $wiadomosc->text }}</p>
        </div>
        @endforeach

        @endif
        @endif
        <button type="button" onclick="closeModal_Wiadomosci()" class="zamknij_but">Anuluj</button>
      </div>
    </div>

    <div id="modal_naduzycie" class="modal">
      <div class="modal_content">
        <h2>Zgłoś naruszenie oferty</h2>
        <form method="POST" action="{{ route('admin.zglos_oferte') }}">
          @csrf
          <input type="hidden" name="id_oferty" id="naduzycie_oferta_id">
          <label>Powód zgłoszenia:</label>
          <textarea name="powod" required placeholder="Opisz dlaczego zgłaszasz tę ofertę..."></textarea>
          <br><br>
          <button type="submit" class="submit_but">Wyślij zgłoszenie</button>
          <button type="button" onclick="closeModal_naduzycie()" class="zamknij_but">Anuluj</button>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script>
    function openModal_banuj(ofertaId) {

      document.getElementById('modal_banuj').style.display = 'block';

      document.getElementById('banuj_oferta_id').value = ofertaId;
    }

    function closeModal_banuj() {

      document.getElementById('modal_banuj').style.display = 'none';
    }

    function openModal_zglos(ofertaId) {

      document.getElementById('modal_zglos').style.display = 'block';

      document.getElementById('modal_oferta_id').value = ofertaId;
    }

    function closeModal_zglos() {

      document.getElementById('modal_zglos').style.display = 'none';
    }

    function openModal_Wiadomosci() {

      document.getElementById('modal_Wiadomosci').style.display = 'block';
    }

    function closeModal_Wiadomosci() {

      document.getElementById('modal_Wiadomosci').style.display = 'none';
    }

    function openModal_naduzycie(ofertaId) {

      document.getElementById('modal_naduzycie').style.display = 'block';

      document.getElementById('naduzycie_oferta_id').value = ofertaId;
    }

    function closeModal_naduzycie() {

      document.getElementById('modal_naduzycie').style.display = 'none';
    }
  </script>

</body>

</html>