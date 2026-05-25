<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/resetpass.css')
</head>

<body>
  <header class="main-header">

    <a href="{{ route('main') }}" class="logo-box">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </a>

    <div class="header-text">
      <h2>Sprzontando</h2>
      <p>Reset password</p>
    </div>

    <div class="header-buttons">
      <a href="{{ route('main') }}">
        <button type="button">Strona główna</button>
      </a>
    </div>

  </header>
  <h2>Nowe hasło</h2>

  <form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <input type="password" name="password" placeholder="Nowe hasło" required>
    <input type="password" name="password_confirmation" placeholder="Powtórz hasło" required>

    <button type="submit">Zmień hasło</button>
  </form>
</body>

</html>