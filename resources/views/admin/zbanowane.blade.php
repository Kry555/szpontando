<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zbanowane Oferty - Panel Admina</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; vertical-align: middle; }
        th { background: #eee; }
        tr:nth-child(even) { background: #f9f9f9; }
        .container { padding: 20px; }
        .btn-unban { background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-unban:hover { background: #218838; }
        .profil-btn { background: #6c757d; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .profil-btn img { border-radius: 50%; object-fit: cover; }
        .tiles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; margin-top: 15px; padding: 5px; }
        .tile { background: #2a2a2a; color: white; border: 1px solid orange; padding: 12px; border-radius: 8px; cursor: pointer; text-align: center; font-size: 13px; transition: transform 0.2s, background 0.2s, box-shadow 0.2s; }
        .tile:hover { transform: translateY(-3px); background: #333; box-shadow: 0 4px 8px rgba(255, 165, 0, 0.3); }
        .tile strong { display: block; color: orange; margin-bottom: 5px; }
    </style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
        <h1>🚫 Zbanowane Oferty</h1>
        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Właściciel</th>
                    <th>Typ</th>
                    <th>Adres</th>
                    <th>Cena</th>
                    <th>Data Zbanowania</th>
                    <th>Opis</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($oferty as $o)
                <tr>
                    <td>{{ $o->id_oferty }}</td>
                    <td>
                        <button class="profil-btn" onclick="openModal_profil(
                            '{{ $o->nick }}',
                            '{{ asset('images/profilowe/' . ($o->profilowe ?? 'default.png')) }}',
                            '{{ $o->imie }}',
                            '{{ $o->nazwisko }}',
                            '{{ $o->miasto ?? '' }}',
                            '{{ $o->email_kontaktowy ?? '' }}',
                            '{{ $o->ocena ?? '0' }}', 
                            '{{ $o->ostatnie_zlecenia ?? "[]" }}')"> {{-- Teraz pobieramy ostatnie zlecenia --}}
                            <span>{{ $o->nick }}</span>
                        </button>
                    </td>
                    <td>{{ $o->typ }}</td>
                    <td>{{ $o->adres }}</td>
                    <td>{{ $o->cena }} zł</td>
                    <td>{{ $o->updated_at }}</td>
                    <td>{{ Str::limit($o->opis, 50) }}</td>
                    <td>
                        <form action="{{ route('admin.odbanuj_oferte') }}" method="POST" style="margin:0;">
                            @csrf
                            <input type="hidden" name="id_oferty" value="{{ $o->id_oferty }}">
                            <button type="submit" class="btn-unban">Odbanuj</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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

    <!-- MODAL SZCZEGÓŁY ZLECENIA (dla historii z modal_profil) -->
    <div id="modal_history_detail" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.85); z-index: 9999;">
        <div style="background:#fff; color:black; padding:25px; width:380px; margin:130px auto; position:relative; border-radius:12px; border-top: 5px solid orange; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <button type="button" onclick="closeModal_historyDetail()" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">✖</button>
            <h2 id="history_detail_title" style="color: orange; margin-top: 0;"></h2>
            <div id="history_detail_content" style="line-height: 1.6; color: #444;"></div>
            <button onclick="closeModal_historyDetail()" style="width:100%; margin-top:20px; padding:10px; background: orange; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Zamknij</button>
        </div>
    </div>

    <script>
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
</body>
</html>