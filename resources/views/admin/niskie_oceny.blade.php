<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sprzontando</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    <style>
        table { width: 100%; border-collapse: collapse; } 
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .profil-btn { background: #6c757d; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .profil-btn img { border-radius: 50%; object-fit: cover; }
        .tiles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; margin-top: 15px; }
        .tile { background: #2a2a2a; color: white; border: 1px solid orange; padding: 12px; border-radius: 8px; text-align: center; font-size: 13px; }
    </style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Użytkownicy ze średnią oceną poniżej 2.5</h1>
    
    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nick</th>
                <th>Ocena</th>
                <th>Status</th>
                <th>Akcja (Banuj)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($uzytkownicy as $u)
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
                        '{{ $u->ocena ?? '0' }}')">
                        <img src="{{ asset('images/profilowe/' . $u->profilowe) }}" alt="P" width="25" height="25">
                        <span>{{ $u->nick }}</span>
                    </button>
                </td>
                <td>{{ $u->ocena }}</td>
                <td>{{ $u->aktywny ? 'Aktywny' : 'Zablokowany' }}</td>
                <td>
                    @if($u->aktywny)
                        <form action="{{ route('admin.ban_user') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $u->id }}">
                            <input type="number" name="dni" placeholder="Liczba dni" required min="1" style="width: 80px;">
                            <input type="text" name="powod" placeholder="Powód" required>
                            <button type="submit">Nałóż ban</button>
                        </form>
                    @else
                        <form action="{{ route('admin.unban_user') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $u->id }}">
                            <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px; cursor: pointer;">
                                Odbanuj
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <!-- MODAL PROFIL (Uproszczony dla Admina) -->
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
        </div>
    </div>

    <script>
        function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena) {
            document.getElementById('modal_profil_img').src = profilowe;
            document.getElementById('modal_profil_nick').textContent = nick;
            document.getElementById('modal_profil_imie').textContent = imie || 'Brak';
            document.getElementById('modal_profil_nazwisko').textContent = nazwisko || '';
            document.getElementById('modal_profil_miasto').textContent = miasto || 'Brak lokalizacji';
            document.getElementById('modal_profil_email').textContent = email || 'Brak kontaktu';
            document.getElementById('modal_profil_ocena').textContent = ocena || '0';
            document.getElementById('modal_profil_standard').style.display = 'block';
        }
        function closeModal_profil() {
            document.getElementById('modal_profil_standard').style.display = 'none';
        }
    </script>
</body>
</html>