<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change profil</title>
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

    <div id="modal_change" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
            <h1>Edytuj profil</h1>
            <form method="POST" action="{{ route('set_profil.post') }}">
                @csrf

                <!-- Profilowe (opcjonalne) -->
                <input type="text" name="profilowe" placeholder="profilowe" value="{{ old('profilowe', $profil->profilowe) }}"><br>
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
    <script>
        function openModal_change() {
            document.getElementById('modal_change').style.display = 'block';
        }

        function closeModal_change() {
            document.getElementById('modal_change').style.display = 'none';
        }
    </script>
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openModal_change();
        });
    </script>
    @endif
    @endauth
    <a href="{{ route('main') }}">
        <button type="button">wróc na główną jełopie</button>
    </a>
</body>

</html>