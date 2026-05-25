<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/set-profil.css')
</head>

<body>
  <header class="main-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>Twój profil</p>
    </div>

    <div class="header-buttons">
      <a href="{{ route('main') }}">
        <button type="button">Strona główna</button>
      </a>
    </div>

  </header>
  @auth

<div class="showprofil">
  <img src="{{ asset('images/profilowe/' . $profil->profilowe) }}" alt="Profilowe" width="100"><br>
  Nick: {{$profil->nick}}<br>
  Imię: {{$profil->imie}}<br>
  Nazwisko: {{$profil->nazwisko}}<br>
  Płeć: {{$profil->sex}}<br>
  Data ur.: {{$profil->data_ur}}<br>
  Miasto: {{$profil->miasto}}<br>
  Emial kontaktowy: {{$profil->email_kontaktowy}}<br>
  Średnie oceny: {{$profil->ocena}}<br>
</div>

<div class="profile-buttons">
  <button onclick="openModal_change()">Edytuj profil</button>
  <button onclick="openModal_change_users()">Edytuj dane logowania</button>
</div>

  <!-- MODAL EDYCJA PROFILU -->
  <div id="modal_change" class="modal">
    <div class="modal_content">

      <h1>Edytuj profil</h1>

      <form method="POST" action="{{ route('set_profil.post') }}" enctype="multipart/form-data">
        @csrf

        <label>Profilowe:</label>
        <input type="file" name="profilowe" accept="image/*">

        @if($profil->profilowe)
        <img src="{{ asset('images/profilowe/' . $profil->profilowe) }}" width="90">
        @endif

        <input type="text" name="nick" value="{{ $profil->nick }}" required>
        <input type="text" name="imie" value="{{ $profil->imie }}" required>
        <input type="text" name="nazwisko" value="{{ $profil->nazwisko }}" required>

        <p>Data urodzenia:</p>
        <input type="datetime-local" name="data_ur"
          value="{{ \Carbon\Carbon::parse($profil->data_ur)->format('Y-m-d\TH:i') }}" required>

        <input type="text" name="miasto" value="{{ $profil->miasto }}" required>
        <input type="email" name="email_kontaktowy" value="{{ $profil->email_kontaktowy }}" required>

        <p>Płeć:</p>
        <label><input type="radio" name="gender" value="men" {{ $profil->sex=='men'?'checked':'' }}> Men</label>
        <label><input type="radio" name="gender" value="women" {{ $profil->sex=='women'?'checked':'' }}> Women</label>
        <label><input type="radio" name="gender" value="slup" {{ $profil->sex=='slup'?'checked':'' }}> Słup</label>

        <button type="submit">Zapisz</button>
        <button type="button" onclick="closeModal_change()">Anuluj</button>
      </form>

    </div>
  </div>


  <!-- MODAL DANE LOGOWANIA -->
  <div id="modal_change_users" class="modal">
    <div class="modal_content">

      <h1>Dane logowania</h1>

      <button onclick="openModal_change_email()">Zmień email</button>

      <form method="GET" action="{{ route('password.request') }}">
        <button type="submit">Zmień hasło</button>
      </form>

      <button onclick="closeModal_change_users()">Anuluj</button>

    </div>
  </div>


  <!-- MODAL EMAIL -->
  <div id="modal_change_email" class="modal">
    <div class="modal_content">

      <h1>Zmień email</h1>

      <form id="emailChangeForm" method="POST" action="{{ route('email.change.request') }}">
        @csrf

        <input type="password" name="password" placeholder="Podaj hasło" required>

        <button type="submit">Wyślij link</button>
      </form>

      <button onclick="goBackToUsersModal()">Wróć</button>

    </div>
  </div>


  <!-- MODAL INFO -->
  <div id="modal_email_sent" class="modal">
    <div class="modal_content">

      <p>Link został wysłany na Twój email.</p>

      <button onclick="closeEmailSentModal()">OK</button>

    </div>
  </div>
  <script>
function openModal(id) {
  document.getElementById(id).classList.add('active');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}

function openModal_change() {
  openModal('modal_change');
}

function closeModal_change() {
  closeModal('modal_change');
}

function openModal_change_users() {
  openModal('modal_change_users');
}

function closeModal_change_users() {
  closeModal('modal_change_users');
}

function openModal_change_email() {
  closeModal('modal_change_users');
  openModal('modal_change_email');
  document.getElementById('emailChangeForm').reset();
}

function closeModal_change_email() {
  closeModal('modal_change_email');
}

function openEmailSentModal() {
  openModal('modal_email_sent');
}

function closeEmailSentModal() {
  closeModal('modal_email_sent');
}

function goBackToUsersModal() {
  closeModal_change_email();
  openModal_change_users();
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
    document.addEventListener('DOMContentLoaded', function() {
      openEmailSentModal();
    });
  </script>
  @endif
  @endauth
</body>

</html>