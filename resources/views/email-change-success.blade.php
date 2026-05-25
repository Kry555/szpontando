<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/succes-email.css')
</head>

<body>
  <header class="main-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>Email-succes</p>
    </div>

    <div class="header-buttons">
      <a href="{{ route('main') }}">
        <button type="button">Strona główna</button>
      </a>
    </div>

  </header>
  Email został zmieniony pomyślnie!
  <a href="{{ route('main') }}">
    <button>Przejdź na stronę główną</button>
  </a>
</body>

</html>