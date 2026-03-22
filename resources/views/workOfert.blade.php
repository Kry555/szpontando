<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyOfert</title>

    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/myOfert.css')
</head>

<body>

    @auth

    <h2>Twoje zgłoszenia</h2>

    @foreach($zgloszenia as $zgloszenie)
    @if($zgloszenie->wiadomosc)

    <div class="zgloszenie">

        <!-- PROFIL -->
        <button class="profil-btn" onclick="openModal_profil(
            '{{ $zgloszenie->nick }}',
            '{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}',
            '{{ $zgloszenie->imie ?? '' }}',
            '{{ $zgloszenie->nazwisko ?? '' }}',
            '{{ $zgloszenie->data_ur ?? 'brak' }}',
            '{{ $zgloszenie->miasto ?? '' }}',
            '{{ $zgloszenie->email_kontaktowy ?? '' }}',
            '{{ $zgloszenie->ocena ?? '' }}',
            '{{ $zgloszenie->sex ?? '' }}'
        )">
            <img src="{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}" alt="Profilowe">
            <span>{{ $zgloszenie->nick }}</span>
        </button>

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
        '{{ $zgloszenie->status ?? 'brak' }}'
    )">
            Szczegóły oferty
        </button>

        <!-- PROFIL -->
        <button class="profil-btn" onclick="openModal_profil(
        '{{ $zgloszenie->nick }}',
        '{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}',
        '{{ $zgloszenie->imie ?? '' }}',
        '{{ $zgloszenie->nazwisko ?? '' }}',
        '{{ $zgloszenie->data_ur ?? 'brak' }}',
        '{{ $zgloszenie->miasto ?? '' }}',
        '{{ $zgloszenie->email_kontaktowy ?? '' }}',
        '{{ $zgloszenie->ocena ?? '' }}',
        '{{ $zgloszenie->sex ?? '' }}'
    )">
            <img src="{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}" alt="Profilowe">
            <span>{{ $zgloszenie->nick }}</span>
        </button>

        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc ?? '' }}</p>
        <p><strong>Status zgłoszenia:</strong> {{ $zgloszenie->status ?? 'brak' }}</p>

        <button type="button" onclick="openModal_termin()">Ustal termin</button>
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
    <!-- ================= MODAL PROFIL ================= -->
    <div id="modal_profil" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px; text-align:center;">
            <button onclick="closeModal_profil()" style="position:absolute; top:10px; right:10px;">✖</button>

            <img id="modal_profil_img" style="width:100px; height:100px; border-radius:50%; margin-bottom:10px;">
            <h2 id="modal_profil_nick"></h2>

            <p><strong>Imię i nazwisko:</strong>
                <span id="modal_profil_imie"></span>
                <span id="modal_profil_nazwisko"></span>
            </p>

            <p><strong>Miasto:</strong> <span id="modal_profil_miasto"></span></p>
            <p><strong>Email:</strong> <span id="modal_profil_email"></span></p>
            <p><strong>Ocena:</strong> <span id="modal_profil_ocena"></span></p>
            <p><strong>Data urodzenia:</strong> <span id="modal_profil_data"></span></p>
            <p><strong>Płeć:</strong> <span id="modal_profil_sex"></span></p>
        </div>
    </div>


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
        // ===== PROFIL =====
        function openModal_profil(nick, profilowe, imie, nazwisko, data_ur, miasto, email, ocena, sex) {
            document.getElementById('modal_profil_img').src = profilowe;
            document.getElementById('modal_profil_nick').textContent = nick;
            document.getElementById('modal_profil_imie').textContent = imie ?? '';
            document.getElementById('modal_profil_nazwisko').textContent = nazwisko ?? '';
            document.getElementById('modal_profil_data').textContent = data_ur ?? 'brak';
            document.getElementById('modal_profil_miasto').textContent = miasto ?? '';
            document.getElementById('modal_profil_email').textContent = email ?? '';
            document.getElementById('modal_profil_ocena').textContent = ocena ?? 'brak';
            document.getElementById('modal_profil_sex').textContent = sex ?? 'brak';

            document.getElementById('modal_profil').style.display = 'block';
        }

        function closeModal_profil() {
            document.getElementById('modal_profil').style.display = 'none';
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
        <button type="button">Wróć na główną</button>
    </a>

</body>

</html>