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

    <h2>Twoje zgłoszenia które oczekuja na zatwierdzenie</h2>

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
            '{{ $zgloszenie->imie ?? "" }}',
            '{{ $zgloszenie->nazwisko ?? "" }}',
            '{{ $zgloszenie->miasto ?? "" }}',
            '{{ $zgloszenie->email_kontaktowy ?? "" }}',
            '{{ $zgloszenie->ocena ?? '0' }}',
            '{{ $zgloszenie->ostatnie_zlecenia ?? "[]" }}'
            )">
            <img src="{{ asset('images/profilowe/' . $zgloszenie->profilowe) }}" alt="Profilowe">
            <span>{{ $zgloszenie->nick }}</span>
        </button>

        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc ?? '' }}</p>
        <p><strong>Status:</strong> {{ $zgloszenie->status ?? $zgloszenie->zgloszenie_status ?? 'brak' }}</p>


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

        // ===== PROFIL =====
        function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena, zleceniaRaw) {
            document.getElementById('modal_profil_img').src = profilowe;
            document.getElementById('modal_profil_nick').textContent = nick;
            document.getElementById('modal_profil_imie').textContent = imie || 'Brak';
            document.getElementById('modal_profil_nazwisko').textContent = nazwisko || '';
            document.getElementById('modal_profil_miasto').textContent = miasto || 'Brak lokalizacji';
            document.getElementById('modal_profil_email').textContent = email || 'Brak kontaktu';
            document.getElementById('modal_profil_ocena').textContent = ocena || '0';

            const container = document.getElementById('modal_profil_zlecenia_container');
            container.innerHTML = '';

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
                container.innerHTML = '<p style="grid-column: 1/-1; color: #999; font-style: italic;">Użytkownik nie posiada jeszcze historii współprac.</p>';
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
    <h1>Nie jesteś zalogowany</h1>
    @endguest


    <a href="{{ route('main') }}">
        <button type="button">Wróć na strone główną</button>
    </a>

</body>

</html>