<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/myOfert.css')
</head>

<body>

    @auth

    <div class="messages" style="padding: 20px;">
        @if(session('success'))
        <div style="color: green; font-weight: bold; border: 1px solid green; padding: 10px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div style="color: red; font-weight: bold; border: 1px solid red; padding: 10px;">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
        @endif
    </div>

    <h2>Twoje zgłoszenia</h2>

    @foreach($zgloszenia as $zgloszenie)
    @if($zgloszenie->wiadomosc)

    <div class="zgloszenie">

        <!-- OFERTA -->
        <button type="button" onclick="openModal_oferta(
            '{{ $zgloszenie->adres ?? '' }}',
            '{{ $zgloszenie->typ ?? '' }}',
            '{{ $zgloszenie->cena ?? '' }}',
            '{{ $zgloszenie->do_kiedy_wazne ?? '' }}',
            '{{ $zgloszenie->opis ?? '' }}',
            '{{ $zgloszenie->oferta_status ?? 'brak' }}'
        )">
            Szczegóły oferty
        </button>

        <!-- INFO -->
        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc }}</p>
        <p><strong>Zatwierdzone:</strong> {{ $zgloszenie->zatwierdzone ? 'Tak' : 'Nie' }}</p>

        <!-- PRZYCISK ANULUJ ZGŁOSZENIE -->
        <form method="POST" action="{{ route('cancelZgloszenie.post') }}">
            @csrf
            <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">

            <button type="submit"
                @if(!in_array($zgloszenie->zgloszenie_status, [null, 'aktywne'])) disabled @endif>
                @if($zgloszenie->zgloszenie_status == 'anulowane')
                Anulowane
                @elseif($zgloszenie->zgloszenie_status == 'zatwierdzone')
                Zatwierdzone
                @elseif($zgloszenie->zgloszenie_status == 'wykonane')
                Wykonane
                @else
                Anuluj zgłoszenie
                @endif
            </button>
        </form>

    </div>

    @endif
    @endforeach
    <h1>Zgloszenia do których cie wybrano</h1>
    @foreach($zgloszeniaWybrane as $zgloszenie)
    <div class="zgloszenie">
        <!-- OFERTA -->
        <button type="button" onclick="openModal_oferta(
        '{{ $zgloszenie->adres ?? '' }}',
        '{{ $zgloszenie->typ ?? '' }}',
        '{{ $zgloszenie->cena ?? '' }}',
        '{{ $zgloszenie->do_kiedy_wazne ?? '' }}',
        '{{ $zgloszenie->opis ?? '' }}',
        '{{ $zgloszenie->status ?? $zgloszenie->oferta_status ?? 'brak' }}'
    )">
            Szczegóły oferty
        </button>

        <!-- PROFIL -->
        <button class="profil-btn" onclick="openModal_profil(
        '{{ $zgloszenie->nick }}',
        '{{ asset('images/profilowe/' . $zgloszenie->profilowe) }}',
        '{{ $zgloszenie->imie ?? '' }}',
        '{{ $zgloszenie->nazwisko ?? '' }}',
        '{{ $zgloszenie->miasto ?? '' }}',
        '{{ $zgloszenie->email_kontaktowy ?? '' }}',
        '{{ $zgloszenie->ocena ?? '' }}',
        '{{ $zgloszenie->ostatnie_zlecenia ?? '' }}'
    )">
            <img src="{{ asset('images/profilowe/' . $zgloszenie->profilowe) }}" alt="Profilowe">
            <span>{{ $zgloszenie->nick }}</span>
        </button>

        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc ?? '' }}</p>
        <p><strong>Status:</strong> {{ $zgloszenie->status ?? $zgloszenie->zgloszenie_status ?? 'brak' }}</p>

        <button type="button" onclick="openModal_termin()">Ustal termin</button>
        <div style="border:1px solid #ccc; padding:10px; margin-top:10px;">

            @if(isset($zgloszenie->proponowany_termin) && $zgloszenie->proponowany_termin)

            <p><strong>Proponowany termin:</strong>
                {{ \Carbon\Carbon::parse($zgloszenie->proponowany_termin)->format('Y-m-d H:i') }}
            </p>

            @if(empty($zgloszenie->ostateczny_termin))

            <!-- STATUS -->
            <p>
                @if(!$zgloszenie->termin_zaakceptowany_wykonawca)
                ⚠️ <strong>Status:</strong> Właściciel zaproponował termin - Twoja decyzja.
                @elseif(!$zgloszenie->termin_zaakceptowany_wlasciciel)
                ⏳ <strong>Status:</strong> Oczekiwanie na decyzję właściciela.
                @endif
            </p>

            <!-- AKCEPTUJ -->
            @if(!$zgloszenie->termin_zaakceptowany_wykonawca)
            <form method="POST" action="{{ route('acceptTerminWorker') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                <button type="submit">✅ Akceptuj</button>
            </form>
            @endif

            <!-- ZMIEŃ -->
            @if(!$zgloszenie->termin_zaakceptowany_wykonawca)
            <form method="POST" action="{{ route('changeTerminWorker') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                <input type="datetime-local" name="termin" required>
                <button type="submit">✏️ Zaproponuj inny</button>
            </form>
            @endif

            @else
            <p><strong>✅ Ustalony termin:</strong> {{ \Carbon\Carbon::parse($zgloszenie->ostateczny_termin)->format('Y-m-d H:i') }}</p>

            {{-- Sekcja oceny gospodarza --}}
            @if(!empty($zgloszenie->ostateczny_termin) && !($zgloszenie->juz_oceniono ?? false))
            <div style="background: #f0f7ff; padding: 10px; border: 1px dashed blue; margin-top: 10px; color: #1e1e1e;">
                <h4>Oceń gospodarza</h4>
                <form method="POST" action="{{ route('ocena.store') }}">
                    @csrf
                    <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                    <input type="hidden" name="id_profil_oceniany" value="{{ $zgloszenie->id_profil_owner ?? '' }}"> {{-- Upewnij się że masz id_profil_owner w select --}}
                    <input type="hidden" name="rola" value="pracownik">
                    <label>Gwiazdki (0-5):</label>
                    <input type="number" name="gwiazdki" min="0" max="5" required><br>
                    <textarea name="opis" placeholder="Jak oceniasz współpracę z gospodarzem?" maxlength="255"></textarea><br>
                    <button type="submit">Wystaw opinię gospodarzowi</button>
                </form>
            </div>
            @endif
            @endif

            @else
            <p>⏳ Właściciel jeszcze nie ustawił terminu</p>
            @endif

        </div>
    </div>
    @endforeach

    <!-- ================= MODAL TERMIN ================= -->
    <div id="modal_termin" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px; text-align:center;">
            <button onclick="closeModal_termin()" style="position:absolute; top:10px; right:10px;">✖</button>

            <h2>Ustal termin</h2>
            <p>Work in progress 🚧</p>
        </div>
    </div>

    @include('partials.profile-modal')

    <!-- ================= MODAL OFERTA ================= -->
    <div id="modal_oferta" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px;">
            <button onclick="closeModal_oferta()" style="position:absolute; top:10px; right:10px;">✖</button>

            <h2>Szczegóły oferty</h2>

            <p><strong>Adres:</strong> <span id="modal_adres"></span></p>
            <p><strong>Typ:</strong> <span id="modal_typ"></span></p>
            <p><strong>Cena:</strong> <span id="modal_cena"></span></p>
            <p><strong>Ważne do:</strong> <span id="modal_wazne"></span></p>
            <p><strong>Opis:</strong> <span id="modal_opis"></span></p>
            <p><strong>Status:</strong> <span id="modal_status"></span></p>
        </div>
    </div>


    <script>
        // ===== TERMIN =====
        function openModal_termin() {
            document.getElementById('modal_termin').style.display = 'block';
        }

        function closeModal_termin() {
            document.getElementById('modal_termin').style.display = 'none';
        }

        // ===== OFERTA =====
        function openModal_oferta(adres, typ, cena, wazne, opis, status) {
            document.getElementById('modal_adres').textContent = adres;
            document.getElementById('modal_typ').textContent = typ;
            document.getElementById('modal_cena').textContent = cena;
            document.getElementById('modal_wazne').textContent = wazne;
            document.getElementById('modal_opis').textContent = opis;
            document.getElementById('modal_status').textContent = status;

            document.getElementById('modal_oferta').style.display = 'block';
        }

        function closeModal_oferta() {
            document.getElementById('modal_oferta').style.display = 'none';
        }
    </script>

    @endauth


    @guest
    <h1>Nie jesteś zalogowany</h1>
    @endguest


    <a href="{{ route('main') }}">
        <button type="button">Wróć na strone główną</button>
    </a>

</body>

</html>