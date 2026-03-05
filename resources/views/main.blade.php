<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}"> -->
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    @vite('resources/css/main.css')
    <!-- to jest do css tylko pamiętaj npm tun dev -->
</head>

<body>
    <h1>szpontando</h1>
    <h2>tutaj poszponcisz sobie i jeszcze zarobisz</h2>
    @auth
    <p>Witaj, {{ auth()->user()->nick }}!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Wyloguj się</button>
    </form>
    @else
    <p>Loguj sie pało a nie jestes taki incognito fapper <a href="{{ route('login') }}">Zatenteguj się</a> lub <a href="{{ route('register.show') }}">Zajeb konto</a>.</p>
    @endauth
    @foreach($oferty_przeglandarka as $oferta)
    <div class="oferty_przeglandarka">

        <h3>{{ $oferta->typ }}</h3>
        <p><strong>Opis:</strong> {{ $oferta->opis }}</p>
        <p><strong>Cena:</strong> {{ $oferta->cena }} zł</p>
        <p><strong>Adres:</strong> {{ $oferta->adres }}</p>
        <p><strong>Ważne do:</strong> {{ $oferta->do_kiedy_wazne }}</p>
        <hr>
        <p>
            <strong>Autor:</strong>
            {{ $oferta->imie }} {{ $oferta->nazwisko }}
        </p>

        @php
        $zgloszony = in_array($oferta->id_oferty, $Zgloszenia_aktywne ?? []);
        @endphp

        @if($zgloszony)
        <button disabled style="background: #ccc; cursor: not-allowed;">
            Już zgłoszony
        </button>
        @else
        <button onclick="openModal({{ $oferta->id_oferty }})">
            Zgłoś się
        </button>
        @endif

    </div>
    @endforeach
    <a href="{{ route('login') }}">
        <button type="button">Sign_in</button>
    </a>
    <a href="{{ route('logoutt') }}">
        <button type="button">logout</button>
    </a>
    <!-- modale_wyskakujące okienka -->
    <!-- Modal -->
    <div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
            <h2>Wyślij wiadomość</h2>

            <form id="modal_form" method="POST" action="{{ route('oferta.wybierz') }}">
                @csrf
                <input type="hidden" name="oferta_id" id="modal_oferta_id" value="">

                <label>Wiadomość:</label>
                <textarea name="wiadomosc" required style="width:100%; height:100px;"></textarea>

                <br><br>
                <button type="submit">Wyślij</button>
                <button type="button" onclick="closeModal()">Anuluj</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(ofertaId) {
            document.getElementById('modal').style.display = 'block';
            document.getElementById('modal_oferta_id').value = ofertaId;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Pokazanie modal po wysłaniu zgłoszenia (session flash)
        @if(session('modal_success'))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const modalContent = document.getElementById('modal_content');

            modal.style.display = 'block';
            modalContent.innerHTML = `
        <h2>Zgłoszenie wysłane!</h2>
        <p>Twoja oferta o ID {{ session('modal_success') }} została zgłoszona.</p>
        <button type="button" onclick="closeModal()">Zamknij</button>
    `;
        });
        @endif
    </script>
</body>

</html>