<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Sprzontando</title>

  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/forgot-password.css')
</head>

<body>

  <!-- HEADER -->

  <header class="reset-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>odzyskaj dostęp do swojego konta</p>
    </div>

    <div class="header-buttons">

      <a href="{{ route('main') }}">
        <button type="button">
          Strona główna
        </button>
      </a>

      <a href="{{ route('login') }}">
        <button type="button">
          Logowanie
        </button>
      </a>

    </div>

  </header>

  <!-- RESET BOX -->

  <main class="reset-container">

    <div class="reset-card">

      <h1>Reset hasła</h1>

      <p class="sub">
        Podaj email aby otrzymać link resetujący hasło
      </p>

      <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <input
          type="email"
          name="email"
          placeholder="Email"
          required>

        <button type="submit" class="reset-btn">
          Wyślij link
        </button>

      </form>

      @if(session('status'))

      <div class="success-box">
        {{ session('status') }}
      </div>

      @endif

    </div>

  </main>

</body>

</html>