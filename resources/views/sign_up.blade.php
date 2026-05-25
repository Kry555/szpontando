<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/sign_up.css')

</head>

<body>
  <!-- HEADER -->

  <header class="reset-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>Stwórz konto</p>
    </div>

    <div class="header-buttons">

      <a href="{{ route('main') }}">
        <button type="button">
          Strona główna
        </button>
      </a>

    </div>

  </header>
  <div class="register-container">

    <h1 class="register-title">Stwórz konto</h1>

    @if ($errors->any())
    <div class="error-box">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">

      @csrf

      <input type="text"
        name="nick"
        placeholder="Pseudonim"
        value="{{ old('nick') }}"
        required>

      <input type="email"
        name="email"
        placeholder="Email"
        value="{{ old('email') }}"
        required>

      <input type="password"
        name="password"
        placeholder="Hasło"
        required>

      <input type="password"
        name="password_confirmation"
        placeholder="Powtórz hasło"
        required>

      <p class="gender-title">Wybierz płeć:</p>

      <div class="gender-box">

        <label class="gender-option">
          <input type="radio"
            id="menCheck"
            name="gender"
            value="men"
            {{ old('gender') == 'men' ? 'checked' : '' }}>
          <span>Men</span>
        </label>

        <label class="gender-option">
          <input type="radio"
            id="womenCheck"
            name="gender"
            value="women"
            {{ old('gender') == 'women' ? 'checked' : '' }}>
          <span>Women</span>
        </label>

        <label class="gender-option">
          <input type="radio"
            id="slupCheck"
            name="gender"
            value="slup"
            {{ old('gender') == 'slup' ? 'checked' : '' }}>
          <span>Słup elektryczny</span>
        </label>

      </div>

      <!-- CAPTCHA -->
      <div class="captcha-box">

        <div class="left-section">

          <input type="checkbox"
            id="captchaCheck"
            name="tapczan"
            value="1"
            {{ old('tapczan') ? 'checked' : '' }}>

          <label for="captchaCheck" class="custom-checkbox"></label>

          <div class="captcha-text">
            Nie jestem robotem
          </div>

        </div>

        <div class="right-section">

          <img src="{{ Vite::asset('resources/images/tapczan.jpg') }}"
            class="zdjtapczan"
            alt="tapczan">

          TAPCZAN<br>
          Prywatność - bezpieczeństwo

        </div>

      </div>

      <div class="button-group">

        <button type="submit" class="submit-btn">
          Utwórz konto
        </button>

        <a href="{{ route('login') }}">
          <button type="button" class="login-btn">
            Zaloguj się
          </button>
        </a>

        <a href="{{ route('main') }}">
          <button type="button" class="back-btn">
            Wróć na stronę główną
          </button>
        </a>

      </div>

    </form>

  </div>

  <script>
    document.getElementById("captchaCheck").addEventListener("change", function() {

      const checkbox = this;
      const customBox = document.querySelector(".custom-checkbox");

      if (checkbox.checked) {

        customBox.style.pointerEvents = "none";

        setTimeout(() => {
          checkbox.classList.add("checked");
        }, 1000);

      }
    });
  </script>

</body>

</html>