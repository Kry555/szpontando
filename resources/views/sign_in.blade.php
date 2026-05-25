<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Sprzontando</title>

  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/sign_in.css')
</head>

<body>

  <!-- HEADER -->

  <header class="login-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>zaloguj się i zacznij sprzątać</p>
    </div>

    <div class="header-buttons">

      <a href="{{ route('main') }}">
        <button type="button">
          Strona główna
        </button>
      </a>

    </div>

  </header>

  <!-- LOGIN -->

  <main class="login-container">

    @auth

    <div class="logged-box">
      <h1>Jesteś już zalogowany </h1>

      <a href="{{ route('main') }}">
        <button type="button">
          Wróć na stronę główną
        </button>
      </a>
    </div>

    @endauth

    @guest

    <div class="login-card">

      <h1>Zaloguj się</h1>

      <p class="sub">
        Witaj ponownie w Sprzontando
      </p>

      <form method="POST" action="/login">

        @csrf

        <input
          type="email"
          name="email"
          placeholder="Email"
          required
          value="{{ old('email') }}">

        <input
          type="password"
          name="password"
          placeholder="Hasło"
          required>

        <!-- WARNING -->

        @if (session('warning'))

        <div class="warning-box">
          {{ session('warning') }}
        </div>

        @endif

        <!-- ERROR -->

        @if (session('error'))

        <div class="error-box">
          {{ session('error') }}
        </div>

        @endif

        <!-- VALIDATION -->

        @if($errors->any())

        <div class="error-box">
          {{ $errors->first() }}
        </div>

        @endif

        <button type="submit" class="login-btn">
          Zaloguj się
        </button>

      </form>

      <div class="extra-buttons">

        <a href="{{ route('password.request') }}">
          <button type="button">
            Zapomniałeś hasła?
          </button>
        </a>

        <a href="{{ route('register.show') }}">
          <button type="button">
            Utwórz konto
          </button>
        </a>

      </div>

    </div>

    @endguest

  </main>

</body>

</html>