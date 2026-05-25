<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Ranking | Sprzontando</title>

  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/ranking.css')
</head>

<body>

  <!-- HEADER -->
  <div class="header">

    <div class="header-left">
      <img src="{{ asset('images/logo.png') }}" alt="logo">
    </div>

    <div class="header-center">
      <h1>Najlepsi Wykonawcy</h1>
      <p>ranking najbardziej kozackich sprzontaczy</p>
    </div>

    <div class="header-right">
      <a href="{{ route('main') }}">
        <button type="button">
          Wróć na stronę główną
        </button>
      </a>
    </div>

  </div>

  <!-- RANKING -->
  <div class="ranking-container">

    @foreach($wykonawcy as $index => $w)

    @php
    $miejsce = $index + 1;
    $klasa = $miejsce <= 3 ? 'top-' . $miejsce : '' ;

      $ikona=match($miejsce) {
      1=> '🥇',
      2 => '🥈',
      3 => '🥉',
      default => '#' . $miejsce
      };
      @endphp

      <div class="rank-item {{ $klasa }}">

        <div class="rank-number">
          {{ $ikona }}
        </div>

        <img
          src="{{ asset('images/profilowe/' . $w->profilowe) }}"
          class="rank-photo"
          alt="profilowe">

        <div class="rank-info">
          <h3>{{ $w->nick }}</h3>

          <small>
            {{ $w->miasto ?? 'Brak lokalizacji' }}
          </small>
        </div>

        <div class="rank-score">
          {{ $w->ocena }} / 5 ⭐
        </div>

      </div>

      @endforeach

  </div>

</body>

</html>