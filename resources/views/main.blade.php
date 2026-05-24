<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
    <!-- <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}"> -->
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/main.css')
    <!-- to jest do css tylko pamiętaj npm tun dev -->      
</head>

<body>
    <div class="początek">
        <img src="{{ asset('images/logo.png') }}" alt="logo">
        <div class="napisy">
            <h2 style="color:orange">tutaj poszponcisz sobie i jeszcze zarobisz</h2>
        </div>
    </div>
    @auth
    <p>Witaj, {{ auth()->user()->nick }}!</p>
    <!-- pzycisk do wylogowywania pzenies tylko ten kod -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Wyloguj się</button>
    </form>

    <!-- pzycisk do dodawania oferty pzenies tylko ten kod-->
    <a href="{{ route('add_ofert') }}">
        <button type="button">Dodaj ofertę</button>
    </a>
    <a href="{{ route('set_profil') }}">
        <button type="button">Ustaw profil</button>
    </a>
    <a href="{{ route('my_ofert') }}">
        <button type="button">Moje oferty</button>
    </a>
    <a href="{{ route('work_ofert') }}">
        <button type="button">Twoje zgłoszenia</button>
    </a>

    @if(auth()->user()->czy_admin)
    <a href="{{ route('admin.dashboard') }}">
        <button type="button" style="background: #dc3545; color: white;">Panel Admina</button>
    </a>
    @endif

    <button onclick="openModal_Wiadomosci()">
        Wiadomosci ({{ is_string($notf) ? 0 : $notf->count() }})
    </button>
    @else
    <p style="text-align:right">utwórz konto już dziś! <a href="{{ route('login') }}">Zaloguj się</a> lub <a href="{{ route('register.show') }}">Zarejestruj się</a>.</p>
    @endauth

    <a href="{{ route('ranking') }}">
        <button type="button" style="background: gold; color: black; font-weight: bold;">Ranking Wykonawców 🏆</button>
    </a>

    <form method="GET" action="{{ route('main') }}">

        <label>Cena od:</label>
        <input type="number" name="cena_min" value="{{ request('cena_min') }}" min="0">

        <label>Cena do:</label>
        <input type="number" name="cena_max" value="{{ request('cena_max') }}" min="0">
        <label>wybierz miasto:</label>
        <form method="GET" action="{{ route('main') }}">
            <input type="text" id="miasto" name="miasto" value="{{ request('miasto') }}" autocomplete="off">
            <label for="typ">Filtruj po typie:</label>


            <select name="typ" id="typ">
                <option value="">-- Wszystkie --</option>

                <optgroup label="Podstawowe">
                    <option value="samochód" {{ request('typ') == 'samochód' ? 'selected' : '' }}>Samochód</option>
                    <option value="rower" {{ request('typ') == 'rower' ? 'selected' : '' }}>Rower</option>
                    <option value="cały_dom" {{ request('typ') == 'cały_dom' ? 'selected' : '' }}>Cały dom</option>
                    <option value="wybrane_pomieszczenia" {{ request('typ') == 'wybrane_pomieszczenia' ? 'selected' : '' }}>Wybrane pomieszczenia</option>
                </optgroup>

                <optgroup label="Specjalistyczne">
                    <option value="brud_ciężki_przemysłowy" {{ request('typ') == 'brud_ciężki_przemyslowy' ? 'selected' : '' }}>Brud ciężki (przemysłowy)</option>
                    <option value="miejsce_zbrodni" {{ request('typ') == 'miejsce_zbrodni' ? 'selected' : '' }}>Miejsce zbrodni</option>
                    <option value="po_remoncie" {{ request('typ') == 'po_remoncie' ? 'selected' : '' }}>Po remoncie</option>
                    <option value="po_imprezie" {{ request('typ') == 'po_imprezie' ? 'selected' : '' }}>Po imprezie</option>
                </optgroup>

                <optgroup label="Zwierzęta">
                    <option value="zwierzęce_zabrudzenia" {{ request('typ') == 'zwierzęce_zabrudzenia' ? 'selected' : '' }}>Zwierzęce zabrudzenia</option>
                    <option value="sprzątanie_po_psie" {{ request('typ') == 'sprzatanie_po_psie' ? 'selected' : '' }}>Sprzątanie po psie</option>
                    <option value="kuweta_kota" {{ request('typ') == 'kuweta_kota' ? 'selected' : '' }}>Kuweta kota</option>
                </optgroup>

                <optgroup label="Inne">
                    <option value="mycie_okien" {{ request('typ') == 'mycie_okien' ? 'selected' : '' }}>Mycie okien</option>
                    <option value="garaż_piwnica" {{ request('typ') == 'garaż_piwnica' ? 'selected' : '' }}>Garaż / piwnica</option>
                    <option value="ogród_tarasy" {{ request('typ') == 'ogród_tarasy' ? 'selected' : '' }}>Ogród / tarasy</option>
                    <option value="dezynfekcja" {{ request('typ') == 'dezynfekcja' ? 'selected' : '' }}>Dezynfekcja</option>
                </optgroup>
            </select>
            <label for="status">Status:</label>

<select name="status" id="status">
    <option value="">-- Wszystkie --</option>

    <option value="aktywna"
        {{ request('status') == 'aktywna' ? 'selected' : '' }}>
        Aktywne
    </option>
    <option value="wygaslo"
        {{ request('status') == 'wygaslo' ? 'selected' : '' }}>
        Wygasłe
    </option>

    <option value="anulowane"
        {{ request('status') == 'anulowane' ? 'selected' : '' }}>
        Anulowane
    </option>
        <option value="zbanowana"
        {{ request('status') == 'zbanowana' ? 'selected' : '' }}>
        zbanowane
    </option>
</select>
            <button type="submit">Filtruj</button>
        </form>
        <div class="oferty">
            @foreach($oferty_przeglandarka as $oferta)
            <div class="oferty_przeglandarka">
                <div class="informacje">
                    <h3>{{ $oferta->typ }}</h3>
                    <p><strong>Opis:</strong> {{ $oferta->opis }}</p>
                    <p><strong>Cena:</strong> {{ $oferta->cena }} zł</p>
                    <p><strong>Adres:</strong> {{ $oferta->adres }}</p>
                    <p><strong>Ważne do:</strong> {{ $oferta->do_kiedy_wazne }}</p>
                    <p><strong>Utworzone:</strong> {{ $oferta->created_at }}</p>
                    <hr>
                    <p>
                        <strong>Autor:</strong>
                        {{ $oferta->imie }} {{ $oferta->nazwisko }}
                    </p>
                </div>
                <div class="guziki">
                    @php
                    $zgloszony = in_array($oferta->id_oferty, $Zgloszenia_aktywne ?? []);
                    @endphp

                    @auth
                    @if($zgloszony)
                    <button disabled style="background: #ccc; cursor: not-allowed;">
                        Już zgłoszony
                    </button>
                    @else
                    <button onclick="openModal_zglos({{ $oferta->id_oferty }})">
                        Zgłoś się
                    </button>
                    <button onclick="openModal_naduzycie({{ $oferta->id_oferty }})" style="background: #dc3545; color: white;">
                        Zgłoś naruszenie
                    </button>
                    @endif
                    @endauth

                    @guest
                    <button disabled style="background:#ccc; cursor:not-allowed;">
                        Zaloguj się aby zgłosić
                    </button>
                    @endguest
                </div>
            </div>
            @endforeach
        </div>

        <!-- na chuj to jest tu dodane -->


        <div class="komentarze">
            <!-- trzeba przeniesc te butony  -->
            <!-- <a href="{{ route('login') }}">
            <button type="button">Sign_in</button>
            </a> -->
            <!-- <a href="{{ route('logoutt') }}">
            <button type="button">logout</button>
            </a> -->
        </div>
        <div class="modale">
            <!-- modale_wyskakujące okienka -->
            <div id="modal_zglos" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
                <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
                    <h2>Wyślij wiadomość</h2>

                    <form id="modal_form" method="POST" action="{{ route('oferta.wybierz') }}">
                        @csrf
                        <input type="hidden" name="oferta_id" id="modal_oferta_id" value="">

                        <label>Wiadomość:</label>
                        <textarea name="wiadomosc" required style="width:100%; height:100px;"></textarea>

                        <br><br>
                        <button type="submit">Wyślij</button>
                        <button type="button" onclick="closeModal_zglos()">Anuluj</button>
                    </form>
                </div>
            </div>
            <div id="modal_Wiadomosci" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
                <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
                    <h2>Wiadomosci</h2>
                    @if(is_string($notf))
                    <p>{{ $notf }}</p>
                    @else

                    @if($notf->isEmpty())
                    <p>Brak nowych powiadomień</p>
                    @else

                    @foreach($notf as $wiadomosc)
                    <div style="border-bottom:1px solid #ccc; margin-bottom:10px;">
                        <strong>{{ $wiadomosc->tytul }}</strong>
                        <p>{{ $wiadomosc->text }}</p>
                    </div>
                    @endforeach

                    @endif
                    @endif
                    <button type="button" onclick="closeModal_Wiadomosci()">Zamknij</button>
                </div>
            </div>

            <!-- Modal zgłaszania naruszenia -->
            <div id="modal_naduzycie" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius: 8px;">
                    <h2>Zgłoś naruszenie oferty</h2>
                    <form method="POST" action="{{ route('admin.zglos_oferte') }}">
                        @csrf
                        <input type="hidden" name="id_oferty" id="naduzycie_oferta_id">
                        <label>Powód zgłoszenia:</label>
                        <textarea name="powod" required style="width:100%; height:100px; margin-top:10px;" placeholder="Opisz dlaczego zgłaszasz tę ofertę..."></textarea>
                        <br><br>
                        <button type="submit" style="background: #dc3545; color: white;">Wyślij zgłoszenie</button>
                        <button type="button" onclick="closeModal_naduzycie()">Anuluj</button>
                    </form>
                </div>
            </div>
        </div>
    <script>
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

        // Pokazanie modal po wysłaniu zgłoszenia (session flash)
        @if(session('modal_success'))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal_zglos');
            const modalContent = document.getElementById('modal_content');

            modal.style.display = 'block';
            modalContent.innerHTML = `
                <h2>Zgłoszenie wysłane!</h2>
                <p>Pomyślnie zgłosiłeś się do oferty o ID {{ session('modal_success') }}.</p>
                <button type="button" onclick="closeModal_zglos()">Zamknij</button>`;
        });
        @endif
    </script>
</body>

</html>