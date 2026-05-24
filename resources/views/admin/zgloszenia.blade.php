<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zgłoszenia nadużyć</title>
    <style>
        table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } .btn-ban { color: red; } .btn-ok { color: green; }
        .profil-btn { background: #6c757d; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .profil-btn img { border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Zgłoszone ogłoszenia</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <table>
        <thead>
            <tr>
                <th>ID Zgłoszenia</th>
                <th>ID Oferty</th>
                <th>Autor Oferty</th>
                <th>Powód</th>
                <th>Opis oferty</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zgloszenia as $z)
            <tr>
                <td>{{ $z->id_zgloszenia }}</td>
                <td>{{ $z->id_oferty }}</td>
                <td>
                    <button class="profil-btn" onclick="openModal_profil(
                        '{{ $z->nick }}',
                        '{{ asset('images/profilowe/' . $z->profilowe) }}',
                        '{{ $z->imie }}',
                        '{{ $z->nazwisko }}',
                        '{{ $z->miasto }}',
                        '{{ $z->email_kontaktowy }}',
                        '{{ $z->ocena ?? '0' }}')">
                        <span>{{ $z->nick }}</span>
                    </button>
                </td>
                <td>{{ $z->powod }}</td>
                <td>{{ Str::limit($z->opis, 100) }}</td>
                <td>
                    <form action="{{ route('admin.rozpatrz_zgloszenie') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="id_zgloszenia" value="{{ $z->id_zgloszenia }}">
                        <input type="hidden" name="decyzja" value="ban">
                        <button type="submit" class="btn-ban">Banuj Ofertę</button>
                    </form>
                    
                    <form action="{{ route('admin.rozpatrz_zgloszenie') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="id_zgloszenia" value="{{ $z->id_zgloszenia }}">
                        <input type="hidden" name="decyzja" value="ok">
                        <button type="submit" class="btn-ok">Jest OK</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
        function closeModal_profil() { document.getElementById('modal_profil_standard').style.display = 'none'; }
    </script>
</body>
</html>