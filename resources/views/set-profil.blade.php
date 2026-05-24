<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    <!-- <link rel="stylesheet" href="{{ asset('css/stop_z_wypalaniem_gał.css') }}"> -->
    @vite('resources/css/stop_z_wypalaniem_gał.css')
</head>

<body>
    @auth
    <h4>To twój profil {{ auth()->user()->nick }}!</h4>
    <div class="showprofil">
        <img src="{{ asset('images/profilowe/' . $profil->profilowe) }}" alt="Profilowe" width="100"><br>
        {{$profil->nick}}<br>
        {{$profil->imie}}<br>
        {{$profil->nazwisko}}<br>
        {{$profil->sex}}<br>
        {{$profil->data_ur}}<br>
        {{$profil->miasto}}<br>
        {{$profil->email_kontaktowy}}<br>
        {{$profil->ocena}}<br>
    </div>
    <button onclick="openModal_change()">
        Edytuj profil
    </button>
    <button onclick="openModal_change_users()">
        Edytuj dane logowania
    </button>

<div id="modal_change_email"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">

    <div style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">

        <h1>Zmień email</h1>

        <form id="emailChangeForm" method="POST" action="{{ route('email.change.request') }}">
            @csrf

            <label>Podaj hasło:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Wyślij link potwierdzający na stary email</button>
        </form>
        <button type="button" onclick="goBackToUsersModal()">Wróć</button>
    </div>
</div>
<div id="modal_email_sent"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(0,0,0,0.5);">

    <div style="background:#fff; color:black; padding:20px; width:400px;
                margin:100px auto; text-align:center;">

        <p>Link do zmiany email został wysłany na Twój aktualny adres.</p>

        <button onclick="closeEmailSentModal()">Wróć</button>
    </div>
</div>

<div id="modal_change" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
    <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
        <h1>Edytuj profil</h1>
            <form method="POST" action="{{ route('set_profil.post') }}" enctype="multipart/form-data">
                @csrf

                <!-- Profilowe (zdjęcie) -->
                <label for="profilowe">Wybierz zdjęcie profilowe:</label><br>
                <input type="file" id="profilowe" name="profilowe" accept="image/*"><br>
                @if($profil->profilowe)
                <img src="{{ asset('images/profilowe/' . $profil->profilowe) }}" alt="Profilowe" width="100" style="margin-top:5px;">
                @endif
                @error('profilowe')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Nick -->
                <input type="text" name="nick" placeholder="nick" value="{{ old('nick', $profil->nick) }}" required><br>
                @error('nick')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Imię -->
                <input type="text" name="imie" placeholder="imie" value="{{ old('imie', $profil->imie) }}" required><br>
                @error('imie')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Nazwisko -->
                <input type="text" name="nazwisko" placeholder="nazwisko" value="{{ old('nazwisko', $profil->nazwisko) }}" required><br>
                @error('nazwisko')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Data urodzenia -->
                 <p>Data urodzenia:</p>
                <input type="datetime-local" name="data_ur"
                    value="{{ old('data_ur', optional($profil->data_ur) ? \Carbon\Carbon::parse($profil->data_ur)->format('Y-m-d\TH:i') : '') }}"
                    required><br>
                @error('data_ur')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Miasto -->
                <input type="text" name="miasto" placeholder="miasto" value="{{ old('miasto', $profil->miasto) }}" required><br>
                @error('miasto')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Email kontaktowy -->
                <input type="email" name="email_kontaktowy" placeholder="email_kontaktowy" value="{{ old('email_kontaktowy', $profil->email_kontaktowy) }}" required><br>
                @error('email_kontaktowy')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Gender -->
                <div class="gender-radio">
                    <input type="radio" id="menCheck" name="gender" value="men"
                        {{ old('gender', $profil->sex) == 'men' ? 'checked' : '' }}>
                    <label for="menCheck">Men</label>
                </div>
                <div class="gender-radio">
                    <input type="radio" id="womenCheck" name="gender" value="women"
                        {{ old('gender', $profil->sex) == 'women' ? 'checked' : '' }}>
                    <label for="womenCheck">Women</label>
                </div>
                <div class="gender-radio">
                    <input type="radio" id="slupCheck" name="gender" value="slup"
                        {{ old('gender', $profil->sex) == 'slup' ? 'checked' : '' }}>
                    <label for="slupCheck">Słup elektryczny</label>
                </div>
                @error('gender')
                <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Zaktualizuj profil</button>
                <button type="button" onclick="closeModal_change()">Anuluj</button>
            </form>

        </div>
    </div>
    <div id="modal_change_users" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
            <h1>Edytuj dane logowania</h1>

<button type="button" onclick="openModal_change_email()">
    Zmień email
</button>

<form method="GET" action="{{ route('password.request') }}">
    <button type="submit">Zmień hasło</button>
</form>

            <!-- tu bendzie dwa guzki zmien email i zmien haslo -->

            <button type="button" onclick="closeModal_change_users()">Anuluj</button>
        </div>
    </div>
    <script>
    function closeModal_change_email() {
        document.getElementById('modal_change_email').style.display = 'none';
    }

        function openModal_change() {
            document.getElementById('modal_change').style.display = 'block';
        }

        function closeModal_change() {
            document.getElementById('modal_change').style.display = 'none';
        }

        function openModal_change_users() {
            document.getElementById('modal_change_users').style.display = 'block';
        }

        function closeModal_change_users() {
            document.getElementById('modal_change_users').style.display = 'none';
        }
        function openEmailSentModal() {
    document.getElementById('modal_email_sent').style.display = 'block';
}


function closeEmailSentModal() {
    document.getElementById('modal_email_sent').style.display = 'none';
}

    function goBackToUsersModal() {
        closeModal_change_email();
        openModal_change_users();
    }

function openModal_change_email() {
    closeModal_change_users();
    document.getElementById('modal_change_email').style.display = 'block';

    document.getElementById('emailChangeForm').reset();
}

</script>
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openModal_change();
        });
    </script>
    @endif
        @if(session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        openEmailSentModal();
    });
</script>
@endif
    @endauth

    <a href="{{ route('main') }}">
        <button type="button">wróc na strone główną </button>
    </a>
</body>

</html>