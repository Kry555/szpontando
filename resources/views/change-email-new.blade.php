<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/change-email.css')
</head>

<body>
  <header class="main-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>New email</p>
    </div>

    <div class="header-buttons">
      <a href="{{ route('main') }}">
        <button type="button">Strona główna</button>
      </a>
    </div>

  </header>
  <h1>Podaj nowy email</h1>

  <form method="POST" action="{{ route('email.change.send.new') }}">
    @csrf

    <input type="hidden"
      name="request_id"
      value="{{ $request_id }}">

    <input type="email"
      name="new_email"
      placeholder="nowy email"
      required>

    <button type="submit">
      Wyślij potwierdzenie
    </button>
  </form>
  @if(session('status'))
  <p>
    {{ session('status') }}
  </p>
  @endif

</body>

</html>