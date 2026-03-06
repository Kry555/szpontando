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
    @endauth
    <div id="modal_change" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
        <div id="modal_content" style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative;">
            <h1>edytuj profil</h1>
            <form method="POST" action="{{ route('set_profil.post') }}">
                @csrf
                <input type="text" name="profilowe" placeholder="profilowe" value="{{ old('profilowe') }}" required><br>
                <input type="text" name="nick" placeholder="nick" value="{{ old('nick') }}" required><br>
                <input type="text" name="imie" placeholder="imie" value="{{ old('imie') }}" required><br>
                <input type="text" name="nazwisko" placeholder="nazwisko" value="{{ old('nazwisko') }}" required><br>
                <input
                    type="datetime-local"
                    name="data_ur"
                    value="{{ old('data_ur') }}"
                    min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
                    required><br>
                <input type="text" name="adres" placeholder="adres" value="{{ old('adres') }}" required><br>
                <input type="text" name="miasto" placeholder="miasto" value="{{ old('miasto') }}" required><br>
                <input type="text" name="email_kontaktowy" placeholder="email_kontaktowy" value="{{ old('email_kontaktowy') }}" required><br>
                <div class="men">
                    <input type="radio" id="menCheck" name="gender" value="men" {{ old('gender') == 'men' ? 'checked' : '' }}>
                    <label for="menCheck" class="custom-checkbox"></label>
                    <span>Men</span>
                </div>

                <div class="women">
                    <input type="radio" id="womenCheck" name="gender" value="women" {{ old('gender') == 'women' ? 'checked' : '' }}>
                    <label for="womenCheck" class="custom-checkbox"></label>
                    <span>Women</span>
                </div>

                <div class="slup">
                    <input type="radio" id="slupCheck" name="gender" value="slup" {{ old('gender') == 'slup' ? 'checked' : '' }}>
                    <label for="slupCheck" class="custom-checkbox"></label>
                    <span>Słup elektryczny</span>
                </div>

                <button type="submit">Wystaw ogloszenie</button>
            </form>
            <button type="button" onclick="closeModal_change()">Anuluj</button>
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
</body>

</html>