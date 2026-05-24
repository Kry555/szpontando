<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/myOfert.css')

    <style>
        .tiles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
            margin-top: 15px;
            padding: 5px;
        }

        .tile {
            background: #2a2a2a;
            color: white;
            border: 1px solid orange;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 13px;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .tile:hover {
            transform: translateY(-3px);
            background: #333;
            box-shadow: 0 4px 8px rgba(255, 165, 0, 0.3);
        }

        .tile strong {
            display: block;
            color: orange;
            margin-bottom: 5px;
        }
    </style>
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
            opis: '{{ $oferta->opis }}',
            zdjecie_1: '{{ $oferta->zdjecie_1 ?? '' }}',
            zdjecie_2: '{{ $oferta->zdjecie_2 ?? '' }}'
        })"
            @if($oferta->status != 'aktywna') disabled @endif>
            Edytuj ofertę
        </button>

        <form method="POST" action="{{ route('zakonczOfert.post') }}">
            @csrf
            <input type="hidden" name="id_oferty" value="{{ $oferta->id_oferty }}">
            <button type="submit"@if($oferta->status == 'anulowane' || $oferta->status == 'zbanowana')disabled @endif>
                Zakończ ofertę
            </button>
        </form>
    </div>
    @endforeach


    <h2>Zgłoszenia do Twoich ofert</h2>
    @foreach($zgloszenia as $zgloszenie)

    @if($zgloszenie->wiadomosc)

    <div class="zgloszenie">

        <!-- PROFIL -->
        <button class="profil-btn" onclick="openModal_profil(
            '{{ $zgloszenie->nick }}',
            '{{ asset('images/profilowe/' . $zgloszenie->profilowe) }}',
            '{{ $zgloszenie->imie }}',
            '{{ $zgloszenie->nazwisko }}',
            '{{ $zgloszenie->miasto }}',
            '{{ $zgloszenie->email_kontaktowy }}',
            '{{ $zgloszenie->ocena ?? '0' }}',
            '{{ $zgloszenie->ostatnie_zlecenia }}'
        )">
            <img src="{{ asset('images/profilowe/' . $zgloszenie->profilowe) }}" alt="Profilowe" width="30">
            <span>{{ $zgloszenie->nick }}</span>
        </button>

        <!-- OFERTA -->
        <button onclick="openModal_oferta(
            '{{ $zgloszenie->adres }}',
            '{{ $zgloszenie->typ }}',
            '{{ $zgloszenie->cena }}',
            '{{ $zgloszenie->do_kiedy_wazne }}',
            '{{ $zgloszenie->opis }}',
            '{{ $zgloszenie->oferta_status }}'
        )">
            Szczegóły zgłoszenia
        </button>

        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc }}</p>

        <!-- AKCEPTACJA -->
        <form method="POST" action="{{ route('acceptOfert.post') }}">
            @csrf
            <input type="hidden" name="id_oferty" value="{{ $zgloszenie->id_oferty }}">
            <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">

            <button type="submit"
                @if($zgloszenie->zatwierdzone || $zgloszenie->status != 'aktywne') disabled @endif>
                Akceptuj zgłoszenie
            </button>
        </form>

        {{--  NEGOCJACJA TERMINU (TU MA BYĆ!) --}}
        @if($zgloszenie->zatwierdzone)

        <div style="border:1px solid #ccc; padding:10px; margin-top:10px;">

            <p><strong>Proponowany termin:</strong>
                {{ $zgloszenie->proponowany_termin ? \Carbon\Carbon::parse($zgloszenie->proponowany_termin)->format('Y-m-d H:i') : 'brak' }}
            </p>

            @if(empty($zgloszenie->ostateczny_termin))
            <p>
                @if(empty($zgloszenie->proponowany_termin))
                 <strong>Status:</strong> Musisz zaproponować termin, aby rozpocząć ustalenia.
                @elseif(!$zgloszenie->termin_zaakceptowany_wlasciciel && $zgloszenie->termin_zaakceptowany_wykonawca)
                 <strong>Status:</strong> Wykonawca zaproponował/zmienił termin - Twoja decyzja.
                @elseif($zgloszenie->termin_zaakceptowany_wlasciciel && !$zgloszenie->termin_zaakceptowany_wykonawca)
                 <strong>Status:</strong> Oczekiwanie na decyzję wykonawcy.
                @endif
            </p>
            @endif

            @if(empty($zgloszenie->proponowany_termin))

            <form method="POST" action="{{ route('setTerminOwner') }}">
                @csrf
                <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                <input type="datetime-local" name="termin" required>
                <button>Ustal termin</button>
            </form>

            @elseif(empty($zgloszenie->ostateczny_termin))

            @if(!$zgloszenie->termin_zaakceptowany_wlasciciel)
            <form method="POST" action="{{ route('acceptTerminOwner') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                <button type="submit">Akceptuj</button>
            </form>
            @endif

            @if(!$zgloszenie->termin_zaakceptowany_wlasciciel)
            <form method="POST" action="{{ route('changeTerminOwner') }}">
                @csrf
                <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                <input type="datetime-local" name="termin" required>
                <button>Zmień termin</button>
            </form>
            @endif

            @else
            <p><strong>✅ Ostateczny termin:</strong>
                {{ \Carbon\Carbon::parse($zgloszenie->ostateczny_termin)->format('Y-m-d H:i') }}
            </p>

            @endif

            {{-- Sekcja oceny pracownika --}}
            @if($zgloszenie->ostateczny_termin && !($zgloszenie->juz_oceniono ?? false))
            <div style="background: #f9f9f9; padding: 10px; border: 1px dashed orange; margin-top: 10px; color: #1e1e1e;">
                <h4>Oceń pracownika</h4>
                <form method="POST" action="{{ route('ocena.store') }}">
                    @csrf
                    <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
                    <input type="hidden" name="id_profil_oceniany" value="{{ $zgloszenie->id_profil_wykonawca }}">
                    <input type="hidden" name="rola" value="gospodarz">
                    <label>Gwiazdki (0-5):</label>
                    <input type="number" name="gwiazdki" min="0" max="5" required><br>
                    <textarea name="opis" placeholder="Krótka opinia słowna..." maxlength="255"></textarea><br>
                    <button type="submit">Wystaw opinię pracownikowi</button>
                </form>
            </div>
            @endif

        </div>

        @endif

    </div>

    @endif
    @endforeach

    <!-- MODAL PROFIL -->
    <div id="modal_profil_standard" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); z-index: 9998;">
        <div style="background:#fff; color:black; padding:25px; width:450px; margin:50px auto; position:relative; border-radius:12px; text-align:center; box-shadow: 0 5px 25px rgba(0,0,0,0.4);">
            <button type="button" onclick="closeModal_profil()" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">✖</button>

            <img id="modal_profil_img" src="" style="width:110px; height:110px; border-radius:50%; margin-bottom:15px; border: 3px solid orange; object-fit: cover;">
            <h2 id="modal_profil_nick" style="margin-top:0; color: #333;"></h2>

            <div style="text-align: left; background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                <p style="margin: 5px 0;"><strong>Imię i nazwisko:</strong> <span id="modal_profil_imie"></span> <span id="modal_profil_nazwisko"></span></p>
                <p style="margin: 5px 0;"><strong>Miasto:</strong> <span id="modal_profil_miasto"></span></p>
                <p style="margin: 5px 0;"><strong>Email:</strong> <span id="modal_profil_email"></span></p>
                <p style="margin: 5px 0;"><strong>Średnia ocena:</strong> <span id="modal_profil_ocena" style="color: #d4af37; font-weight: bold;"></span> / 5 ⭐</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;">Ostatnie zlecenia (kliknij kafel)</h3>
            <div id="modal_profil_zlecenia_container" class="tiles-grid"></div>
        </div>
    </div>

    <!-- MODAL SZCZEGÓŁY ZLECENIA -->
    <div id="modal_history_detail" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.85); z-index: 9999;">
        <div style="background:#fff; color:black; padding:25px; width:380px; margin:130px auto; position:relative; border-radius:12px; border-top: 5px solid orange; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <button type="button" onclick="closeModal_historyDetail()" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">✖</button>
            <h2 id="history_detail_title" style="color: orange; margin-top: 0;"></h2>
            <div id="history_detail_content" style="line-height: 1.6; color: #444;"></div>
            <button onclick="closeModal_historyDetail()" style="width:100%; margin-top:20px; padding:10px; background: orange; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Zamknij</button>
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
            <form method="POST" action="{{ route('edit_offer.post') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_oferty" id="edit_offer_id">

                <p>Rodzaj sprzątania (wybierz jeden):</p>
                <div id="edit_typ_radio">
                    <!-- tutaj JS wstawi radio buttony jak w add_ofert -->
                </div>

                <input type="text" name="adres" id="edit_adres" placeholder="adres" required><br>
                <input type="number" name="cena" id="edit_cena" placeholder="cena" min="0" step="0.01" required> <span>zł</span><br>
                Ważne do:<br>
                <input type="datetime-local" name="do_kiedy_wazne" id="edit_do_kiedy_wazne" required><br>
                <input type="text" name="opis" id="edit_opis" placeholder="opis" required><br>

                <p>Zdjęcia do ogłoszenia (max 2):</p>
                <div id="current_zdjecie_1_display"></div>
                <input type="file" name="zdjecie_1" accept="image/*"><br>
                <label><input type="checkbox" name="clear_zdjecie_1" value="1"> Usuń zdjęcie 1</label><br>
                <br>

                <div id="current_zdjecie_2_display"></div>
                <input type="file" name="zdjecie_2" accept="image/*"><br>
                <label><input type="checkbox" name="clear_zdjecie_2" value="1"> Usuń zdjęcie 2</label><br>
                <br>

                <button type="submit">Zapisz zmiany</button>
                <button type="button" onclick="closeModal_editOffer()">Anuluj</button>
            </form>
        </div>
    </div>


    <script>
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

            // Wyświetlanie istniejących zdjęć i resetowanie pól input
            const currentZdjecie1Display = document.getElementById('current_zdjecie_1_display');
            currentZdjecie1Display.innerHTML = '';
            if (oferta.zdjecie_1) {
                currentZdjecie1Display.innerHTML = `<p>Aktualne zdjęcie 1:</p><img src="{{ asset('images/oferty/') }}/${oferta.zdjecie_1}" style="max-width: 100px; max-height: 100px; margin-bottom: 5px;"><br>`;
            }

            const currentZdjecie2Display = document.getElementById('current_zdjecie_2_display');
            currentZdjecie2Display.innerHTML = '';
            if (oferta.zdjecie_2) {
                currentZdjecie2Display.innerHTML = `<p>Aktualne zdjęcie 2:</p><img src="{{ asset('images/oferty/') }}/${oferta.zdjecie_2}" style="max-width: 100px; max-height: 100px; margin-bottom: 5px;"><br>`;
            }

            // Resetuj pola input typu file i checkboxy
            document.querySelector('#modal_edit_offer input[name="zdjecie_1"]').value = '';
            document.querySelector('#modal_edit_offer input[name="clear_zdjecie_1"]').checked = false;
            document.querySelector('#modal_edit_offer input[name="zdjecie_2"]').value = '';
            document.querySelector('#modal_edit_offer input[name="clear_zdjecie_2"]').checked = false;

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

        function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena, zleceniaRaw) {
            document.getElementById('modal_profil_img').src = profilowe;
            document.getElementById('modal_profil_nick').textContent = nick;
            document.getElementById('modal_profil_imie').textContent = imie || 'Brak';
            document.getElementById('modal_profil_nazwisko').textContent = nazwisko || '';
            document.getElementById('modal_profil_miasto').textContent = miasto || 'Brak lokalizacji';
            document.getElementById('modal_profil_email').textContent = email || 'Brak kontaktu';
            document.getElementById('modal_profil_ocena').textContent = ocena || '0';

            const container = document.getElementById('modal_profil_zlecenia_container');
            container.innerHTML = '<p style="color:orange;">Ładowanie historii...</p>';

            let zleceniaArray = [];
            try {
                zleceniaArray = JSON.parse(zleceniaRaw);
            } catch (e) {
                zleceniaArray = [];
            }


            if (zleceniaArray.length > 0) {
                zleceniaArray.forEach(z => {
                    const tile = document.createElement('div');
                    tile.className = 'tile';
                    tile.innerHTML = `<strong>Zlecenie</strong>${z.typ.replace('_', ' ')}`;
                    tile.onclick = (e) => {
                        e.stopPropagation();
                        openModal_historyDetail(z);
                    };
                    container.appendChild(tile);
                });
            } else {
                container.innerHTML = '<p style="grid-column: 1/-1; color: #999; font-style: italic;">Użytkownik nie posiada jeszcze historii zleceń.</p>';
            }

            document.getElementById('modal_profil_standard').style.display = 'block';
        }

        function closeModal_profil() {
            document.getElementById('modal_profil_standard').style.display = 'none';
        }

        function openModal_historyDetail(data) { // Przyjmuje cały obiekt danych
            document.getElementById('history_detail_title').textContent = data.typ.replace('_', ' ');

            let reviewHtml = '';
            if (data.autor_nick) {
                reviewHtml = `
                    <div style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid orange;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <img src="/images/profilowe/${data.autor_foto}" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; border: 1px solid #ddd; object-fit: cover;">
                            <strong>${data.autor_nick}</strong>
                        </div>
                        <p style="margin: 5px 0; color: #d4af37; font-weight: bold;">Ocena: ${data.gwiazdki} / 5 ⭐</p>
                        <p style="margin: 5px 0; font-style: italic; color: #555;">"${data.opinia_tekst || 'Brak komentarza słownego'}"</p>
                    </div>
                `;
            } else {
                reviewHtml = `<p style="color: #999; font-style: italic; margin-top: 15px;">To zlecenie nie otrzymało jeszcze opinii.</p>`;
            }

            document.getElementById('history_detail_content').innerHTML = `
                <div style="text-align: left; background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
                    <p style="margin: 5px 0;"><strong>Adres:</strong> ${data.adres || 'Brak'}</p>
                    <p style="margin: 5px 0;"><strong>Cena:</strong> ${data.cena || '0'} zł</p>
                    <p style="margin: 5px 0;"><strong>Ważne do:</strong> ${data.do_kiedy_wazne || 'Brak'}</p>
                    <p style="margin: 5px 0;"><strong>Opis:</strong> ${data.oferta_opis || 'Brak opisu'}</p>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <p><strong>Opinia o tym użytkowniku:</strong></p>
                ${reviewHtml}
            `;
            document.getElementById('modal_history_detail').style.display = 'block';
        }

        function closeModal_historyDetail() {
            document.getElementById('modal_history_detail').style.display = 'none';
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