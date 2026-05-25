<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/activate-account.css')
</head>

<body>
  header class="main-header">

  <a href="{{ route('main') }}" class="logo-box">
    <img src="{{ asset('images/logo.png') }}" alt="logo">
  </a>

  <div class="header-text">
    <h2>Sprzontando</h2>
    <p>Aktywuj swoje konto</p>
  </div>

  <div class="header-buttons">
    <a href="{{ route('main') }}">
      <button type="button">Strona główna</button>
    </a>
  </div>

  </header>


  <p>Sprawdź swoją skrzynkę email i kliknij link aktywacyjny.</p>

  <a href="{{ route('main') }}">
    <button>Przejdź na stronę główną</button>
  </a>

</body>

</html>