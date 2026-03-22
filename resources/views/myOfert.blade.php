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

    <h1>Twoje oferty</h1>
    @foreach($oferty as $oferta)
    <div class="oferta">
        <p><strong>Adres:</strong> {{ $oferta->adres }}</p>
        <p><strong>Typ:</strong> {{ $oferta->typ }}</p>
        <p><strong>Cena:</strong> {{ $oferta->cena }}</p>
        <p><strong>Ważne do:</strong> {{ $oferta->do_kiedy_wazne }}</p>
        <p><strong>Opis:</strong> {{ $oferta->opis }}</p>
        <p><strong>Status:</strong> {{ $oferta->status }}</p>
        <button onclick="openModal_editOffer({
            id_oferty: '{{ $oferta->id_oferty }}',
            adres: '{{ $oferta->adres }}',
            typ: '{{ $oferta->typ }}',
            cena: '{{ $oferta->cena }}',
            do_kiedy_wazne: '{{ $oferta->do_kiedy_wazne }}',
            opis: '{{ $oferta->opis }}'
            })">
            Edytuj ofertę
        </button>

        <form method="POST" action="{{ route('zakonczOfert.post') }}">
            @csrf
            <input type="hidden" name="id_oferty" value="{{ $oferta->id_oferty }}">
            <button type="submit" @if($oferta->status == 'anulowane') disabled @endif>
                Zakończ ofertę
            </button>
        </form>
    </div>
    @endforeach


    <h2>Zgłoszenia do Twoich ofert</h2>
    @foreach($zgloszenia as $zgloszenie)
    @if($zgloszenie->wiadomosc)
    <div class="zgloszenie">
        <button class="profil-btn" onclick="openModal_profil(
            '{{ $zgloszenie->nick }}',
            '{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}',
            '{{ $zgloszenie->imie ?? '' }}',
            '{{ $zgloszenie->nazwisko ?? '' }}',
            '{{ $zgloszenie->miasto ?? '' }}',
            '{{ $zgloszenie->email_kontaktowy ?? '' }}',
            '{{ $zgloszenie->ocena ?? '' }}'
        )">
            <img src="{{ asset('images/profilowe/' . ($zgloszenie->profilowe ?? 'default.png')) }}" alt="Profilowe">
            <span>{{ $zgloszenie->nick }}</span>
        </button>
        <button type="button" onclick="openModal_oferta(
            '{{ $zgloszenie->adres }}',
            '{{ $zgloszenie->typ }}',
            '{{ $zgloszenie->cena }}',
            '{{ $zgloszenie->do_kiedy_wazne }}',
            '{{ $zgloszenie->opis }}',
            '{{ $zgloszenie->status }}'
        )">
            Szczegóły zgłoszenia
        </button>

        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc }}</p>
        <p><strong>Zatwierdzone:</strong> {{ $zgloszenie->zatwierdzone ? 'Tak' : 'Nie' }}</p>
        <form method="POST" action="{{ route('acceptOfert.post') }}">
            @csrf
            <input type="hidden" name="id_oferty" value="{{ $zgloszenie->id_oferty }}">
            <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">

            <button type="submit"
                @if($zgloszenie->zatwierdzone || $zgloszenie->status != 'aktywne') disabled @endif>

                @if($zgloszenie->zatwierdzone)
                Już zaakceptowane
                @elseif($zgloszenie->status != 'aktywne')
                Niedostępne
                @else
                Zakceptuj zgłoszenie
                @endif
            </button>
        </form>

    </div>
    @endif
    @endforeach


    <!-- MODAL PROFIL -->
    <div id="modal_profil" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px; text-align:center;">
            <button type="button" onclick="closeModal_profil()" style="position:absolute; top:10px; right:10px;">✖</button>

            <img id="modal_profil_img" src="" style="width:100px; height:100px; border-radius:50%; margin-bottom:10px;">
            <h2 id="modal_profil_nick"></h2>
            <p><strong>Imię i nazwisko:</strong> <span id="modal_profil_imie"></span> <span id="modal_profil_nazwisko"></span></p>
            <p><strong>Miasto:</strong> <span id="modal_profil_miasto"></span></p>
            <p><strong>Email:</strong> <span id="modal_profil_email"></span></p>
            <p><strong>Ocena:</strong> <span id="modal_profil_ocena"></span></p>
        </div>
    </div>

    <!-- MODAL OFERTA -->
    <div id="modal_oferta" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px;">
            <button onclick="closeModal_oferta()" style="position:absolute; top:10px; right:10px;">✖</button>

            <div id="modal_oferta_body">
                <h2>Szczegóły oferty</h2>
                <p><strong>Adres:</strong> <span id="modal_adres"></span></p>
                <p><strong>Typ:</strong> <span id="modal_typ"></span></p>
                <p><strong>Cena:</strong> <span id="modal_cena"></span></p>
                <p><strong>Ważne do:</strong> <span id="modal_wazne"></span></p>
                <p><strong>Opis:</strong> <span id="modal_opis"></span></p>
                <p><strong>Status:</strong> <span id="modal_status"></span></p>
            </div>
        </div>
    </div>

    <!-- MODAL EDYCJA OFERTY -->
    <div id="modal_edit_offer" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div style="background:#fff; color:black; padding:20px; width:450px; margin:50px auto; position:relative; border-radius:10px;">
            <button onclick="closeModal_editOffer()" style="position:absolute; top:10px; right:10px;">✖</button>
            <h2>Edytuj ofertę</h2>
            <form method="POST" action="{{ route('edit_offer.post') }}">
                @csrf
                <input type="hidden" name="id_oferty" id="edit_offer_id">

                <p>Rodzaj sprzątania (wybierz jeden):</p>
                <div id="edit_typ_radio">
                    <!-- tutaj JS wstawi radio buttony jak w add_ofert -->
                </div>

                <input type="text" name="adres" id="edit_adres" placeholder="adres" required><br>
                <input type="number" name="cena" id="edit_cena" placeholder="cena" min="0" step="0.01" required> <span>zł</span><br>
                <input type="datetime-local" name="do_kiedy_wazne" id="edit_do_kiedy_wazne" required><br>
                <input type="text" name="opis" id="edit_opis" placeholder="opis" required><br>

                <button type="submit">Zapisz zmiany</button>
                <button type="button" onclick="closeModal_editOffer()">Anuluj</button>
            </form>
        </div>
    </div>


    <script>
        // MODAL PROFIL
        function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena) {
            document.getElementById('modal_profil_img').src = profilowe;
            document.getElementById('modal_profil_nick').textContent = nick;
            document.getElementById('modal_profil_imie').textContent = imie ?? '';
            document.getElementById('modal_profil_nazwisko').textContent = nazwisko ?? '';
            document.getElementById('modal_profil_miasto').textContent = miasto ?? '';
            document.getElementById('modal_profil_email').textContent = email ?? '';
            document.getElementById('modal_profil_ocena').textContent = ocena ?? '';

            document.getElementById('modal_profil').style.display = 'block';
        }

        function closeModal_profil() {
            document.getElementById('modal_profil').style.display = 'none';
        }

        // MODAL OFERTA
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

        // OTWIERANIE MODALA EDYCJI OFERTY
        const typy = [{
                value: 'samochod',
                label: 'Samochód'
            },
            {
                value: 'rower',
                label: 'Rower'
            },
            {
                value: 'caly_dom',
                label: 'Cały dom'
            },
            {
                value: 'wybrane_pomieszczenia',
                label: 'Wybrane pomieszczenia'
            },
            {
                value: 'brud_ciezki_przemyslowy',
                label: 'Brud ciężki (przemysłowy)'
            },
            {
                value: 'miejsce_zbrodni',
                label: 'Miejsce zbrodni'
            },
            {
                value: 'po_remoncie',
                label: 'Po remoncie'
            },
            {
                value: 'po_imprezie',
                label: 'Po imprezie'
            },
            {
                value: 'zwierzece_zabrudzenia',
                label: 'Zwierzęce zabrudzenia'
            },
            {
                value: 'sprzatanie_po_psie',
                label: 'Sprzątanie po psie'
            },
            {
                value: 'kuweta_kota',
                label: 'Kuweta kota'
            },
            {
                value: 'mycie_okien',
                label: 'Mycie okien'
            },
            {
                value: 'garaz_piwnica',
                label: 'Garaż / piwnica'
            },
            {
                value: 'ogrod_tarasy',
                label: 'Ogród / tarasy'
            },
            {
                value: 'dezynfekcja',
                label: 'Dezynfekcja'
            }
        ];

        function openModal_editOffer(oferta) {
            document.getElementById('edit_offer_id').value = oferta.id_oferty;
            document.getElementById('edit_adres').value = oferta.adres;
            document.getElementById('edit_cena').value = oferta.cena;
            document.getElementById('edit_do_kiedy_wazne').value = oferta.do_kiedy_wazne;
            document.getElementById('edit_opis').value = oferta.opis;

            const container = document.getElementById('edit_typ_radio');
            container.innerHTML = '';

            typy.forEach(t => {
                const label = document.createElement('label');
                const input = document.createElement('input');
                input.type = 'radio';
                input.name = 'typ';
                input.value = t.value;

                if (t.value === oferta.typ) {
                    input.checked = true;
                }

                label.appendChild(input);
                label.insertAdjacentText('beforeend', ` ${t.label}`);
                container.appendChild(label);
                container.appendChild(document.createElement('br'));
            });

            document.getElementById('modal_edit_offer').style.display = 'block';
        }


        function closeModal_editOffer() {
            document.getElementById('modal_edit_offer').style.display = 'none';
        }
    </script>

    @endauth


    @guest

    <h1>nie zalogowałeś się</h1>

    @endguest


    <a href="{{ route('main') }}">
        <button type="button">wróc na strone główną </button>
    </a>


</body>

</html>