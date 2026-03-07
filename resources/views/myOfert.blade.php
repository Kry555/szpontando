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
        <p><strong>Wiadomość:</strong> {{ $zgloszenie->wiadomosc }}</p>
        <p><strong>Zatwierdzone:</strong> {{ $zgloszenie->zatwierdzone ? 'Tak' : 'Nie' }}</p>
        <form method="POST" action="{{ route('acceptOfert.post') }}">
            @csrf
            <input type="hidden" name="id_oferty" value="{{ $zgloszenie->id_oferty }}">
            <input type="hidden" name="id_zgloszenia" value="{{ $zgloszenie->id_zgloszenia }}">
            <button type="submit" @if($zgloszenie->zatwierdzone) disabled @endif>
                Zakceptuj zgłoszenie
            </button>
        </form>
    </div>
    @endif
    @endforeach


    <!-- MODAL PROFIL -->

    <div id="modal_profil" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">

        <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px;">

            <button type="button" onclick="closeModal_profil()" style="position:absolute; top:10px; right:10px;">✖</button>

            <div id="modal_profil_body" style="text-align:center;"></div>

        </div>

    </div>


    <!-- MODAL OFERTA -->

    <div id="modal_oferta" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">

        <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:10px;">

            <button onclick="closeModal_oferta()" style="position:absolute; top:10px; right:10px;">✖</button>

            <div id="modal_oferta_body"></div>

        </div>

    </div>


    <script>
        function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena) {
            const body = document.getElementById('modal_profil_body');

            body.innerHTML = `
        <img src="${profilowe}" style="width:100px;height:100px;border-radius:50%;margin-bottom:10px;">
        <h2>${nick}</h2>
        <p><strong>Imię i nazwisko:</strong> ${imie} ${nazwisko}</p>
        <p><strong>Miasto:</strong> ${miasto}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Ocena:</strong> ${ocena}</p>
    `;

            document.getElementById('modal_profil').style.display = 'block';
        }


        function closeModal_profil() {
            document.getElementById('modal_profil').style.display = 'none';
        }



        function openModal_oferta(adres, typ, cena, wazne, opis, status) {
            const body = document.getElementById('modal_oferta_body');

            body.innerHTML = `
        <h2>Szczegóły oferty</h2>
        <p><strong>Adres:</strong> ${adres}</p>
        <p><strong>Typ:</strong> ${typ}</p>
        <p><strong>Cena:</strong> ${cena}</p>
        <p><strong>Ważne do:</strong> ${wazne}</p>
        <p><strong>Opis:</strong> ${opis}</p>
        <p><strong>Status:</strong> ${status}</p>
    `;

            document.getElementById('modal_oferta').style.display = 'block';
        }


        function closeModal_oferta() {
            document.getElementById('modal_oferta').style.display = 'none';
        }
    </script>

    @endauth


    @guest

    <h1>nie zalogowales sie</h1>

    @endguest


    <a href="{{ route('main') }}">
        <button type="button">wróc na główną jełopie</button>
    </a>


</body>

</html>