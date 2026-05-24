<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sprzontando</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    <style>
        .card { border: 1px solid #ccc; padding: 20px; max-width: 500px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
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
    <h1>Szukaj użytkownika</h1>

    <form action="{{ route('admin.user_stats') }}" method="GET">
        <input type="text" name="search" placeholder="Nick lub ID użytkownika" value="{{ request('search') }}">
        <button type="submit">Szukaj</button>
    </form>

    @if(isset($allUsers))
        <h3>Wszyscy użytkownicy</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nick (Profil)</th>
                    <th>Ocena</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allUsers as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>
                        <button class="profil-btn" onclick="openModal_profil(
                            '{{ $u->nick }}',
                            '{{ asset('images/profilowe/' . $u->profilowe) }}',
                            '{{ $u->imie }}',
                            '{{ $u->nazwisko }}',
                            '{{ $u->miasto }}',
                            '{{ $u->email_kontaktowy }}',
                            '{{ $u->ocena ?? '0' }}',
                            '{{ $u->ostatnie_zlecenia }}')">
                            <img src="{{ asset('images/profilowe/' . ($u->profilowe ?? 'default.png')) }}" width="25" height="25">
                            <span>{{ $u->nick }}</span>
                        </button>
                    </td>
                    <td>{{ $u->ocena }}</td>
                    <td>{{ $u->aktywny ? 'Aktywny' : 'Zbanowany' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;">Brak użytkowników w systemie.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if(session('error')) <p style="color: red; font-weight: bold;">{{ session('error') }}</p> @endif

    @if(isset($user))
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h2>Statystyki dla: {{ $user->nick }}</h2>
                <button class="profil-btn" onclick="openModal_profil(
                            '{{ $user->nick }}',
                            '{{ asset('images/profilowe/' . $user->profilowe) }}',
                            '{{ $user->imie }}',
                            '{{ $user->nazwisko }}',
                            '{{ $user->miasto }}',
                            '{{ $user->email_kontaktowy }}',
                            '{{ $user->ocena ?? '0' }}',
                            '{{ $user->ostatnie_zlecenia }}')">
                    Podgląd Profilu
                </button>
            </div>
            
            <p><strong>Konto od:</strong> {{ $user->created_at }}</p>
            <p><strong>Aktualna ocena:</strong> {{ $user->ocena ?? 'Brak ocen' }} / 5</p>
            <hr>
            <p><strong>Ilość ofert (wszystkich):</strong> {{ $iloscOfert }}</p>
            <p><strong>Oferty zaakceptowane:</strong> {{ $zaakceptowaneOferty }}</p>
            <p><strong>Skuteczność:</strong> 
                @if($iloscOfert > 0)
                    {{ round(($zaakceptowaneOferty / $iloscOfert) * 100, 2) }}%
                @else
                    0%
                @endif
            </p>
            <p><strong>Status:</strong> {{ $user->aktywny ? 'Aktywny' : 'Zablokowany' }}</p>
            @if($user->zbanowany_do)
                <p style="color: red;"><strong>Zbanowany do:</strong> {{ $user->zbanowany_do }}</p>
            @endif

            @if($user->aktywny)
                <form action="{{ route('admin.ban_user') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <input type="number" name="dni" placeholder="Dni bana" required min="1">
                    <input type="text" name="powod" placeholder="Powód bana" required>
                    <button type="submit">Banuj czasowo</button>
                </form>
            @else
                <form action="{{ route('admin.unban_user') }}" method="POST" style="margin-top: 10px;">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                        Odbanuj użytkownika
                    </button>
                </form>
            @endif
        </div>
    @elseif(request('search'))
        <p>Nie znaleziono użytkownika o podanym nicku lub ID.</p>
    @endif

    @if(isset($userLogs))
        <hr>
        <h3>Historia logów administracyjnych dla tego użytkownika</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="border: 1px solid #ccc; padding: 8px;">Data</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Admin</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Akcja</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Szczegóły</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userLogs as $log)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->created_at }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->admin_nick }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->action }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->details }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; padding: 10px;">Brak wpisów w dzienniku dla tego użytkownika.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
    </div>

    <!-- MODAL PROFIL -->
    <div id="modal_profil_standard" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); z-index: 9998;">
        <div style="background:#fff; color:black; padding:25px; width:450px; margin:50px auto; position:relative; border-radius:12px; text-align:center;">
            <button type="button" onclick="closeModal_profil()" style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">✖</button>
            <img id="modal_profil_img" src="" style="width:110px; height:110px; border-radius:50%; margin-bottom:15px; border: 3px solid orange; object-fit: cover;">
            <h2 id="modal_profil_nick" style="margin-top:0;"></h2>
            <div style="text-align: left; background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 8px;">
                <p><strong>Imię i nazwisko:</strong> <span id="modal_profil_imie"></span> <span id="modal_profil_nazwisko"></span></p>
                <p><strong>Miasto:</strong> <span id="modal_profil_miasto"></span></p>
                <p><strong>Email:</strong> <span id="modal_profil_email"></span></p>
                <p><strong>Średnia ocena:</strong> <span id="modal_profil_ocena"></span> / 5 ⭐</p>
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <h3 style="margin-bottom: 10px; font-size: 1.1em;">Ostatnie zlecenia</h3>
            <div id="modal_profil_zlecenia_container" class="tiles-grid"></div>
        </div>
    </div>

    <!-- MODAL SZCZEGÓŁY ZLECENIA -->
    <div id="modal_history_detail" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.85); z-index: 9999;">
        <div style="background:#fff; color:black; padding:25px; width:380px; margin:130px auto; position:relative; border-radius:12px; border-top: 5px solid orange;">
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
            try { zleceniaArray = JSON.parse(zleceniaRaw); } catch (e) { zleceniaArray = []; }

            if (zleceniaArray.length > 0) {
                zleceniaArray.forEach(z => {
                    const tile = document.createElement('div');
                    tile.className = 'tile';
                    tile.innerHTML = `<strong>Zlecenie</strong>${z.typ.replace('_', ' ')}`;
                    tile.onclick = (e) => { e.stopPropagation(); openModal_historyDetail(z); };
                    container.appendChild(tile);
                });
            } else {
                container.innerHTML = '<p style="grid-column: 1/-1; color: #999; font-style: italic;">Brak historii współprac.</p>';
            }
            document.getElementById('modal_profil_standard').style.display = 'block';
        }
        function closeModal_profil() { document.getElementById('modal_profil_standard').style.display = 'none'; }

        function openModal_historyDetail(data) {
            document.getElementById('history_detail_title').textContent = data.typ.replace('_', ' ');
            let reviewHtml = data.autor_nick ? `
                <div style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid orange;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <img src="/images/profilowe/${data.autor_foto}" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                        <strong>${data.autor_nick}</strong>
                    </div>
                    <p style="margin: 5px 0; color: #d4af37; font-weight: bold;">Ocena: ${data.gwiazdki} / 5 ⭐</p>
                    <p style="margin: 5px 0; font-style: italic; color: #555;">"${data.opinia_tekst || 'Brak komentarza'}"</p>
                </div>` : `<p style="color: #999; font-style: italic; margin-top: 15px;">To zlecenie nie otrzymało jeszcze opinii.</p>`;

            document.getElementById('history_detail_content').innerHTML = `
                <div style="text-align: left; background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
                    <p><strong>Adres:</strong> ${data.adres || 'Brak'}</p>
                    <p><strong>Cena:</strong> ${data.cena || '0'} zł</p>
                    <p><strong>Opis:</strong> ${data.oferta_opis || 'Brak opisu'}</p>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <p><strong>Opinia o tym użytkowniku:</strong></p>
                ${reviewHtml}`;
            document.getElementById('modal_history_detail').style.display = 'block';
        }
        function closeModal_historyDetail() { document.getElementById('modal_history_detail').style.display = 'none'; }
    </script>
</body>
</html>