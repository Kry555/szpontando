<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/admin.css')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  @include('admin.sidebar')


  <div class="container">
    <h1>Panel Administratora</h1>

    <div class="grid">
      <a href="{{ route('admin.zgloszenia') }}" class="card">
        <h3>Zgłoszenia</h3>
        <p>Przejrzyj zgłoszone ogłoszenia</p>
        @if($stats['nowe_zgloszenia'] > 0)
        <span class="badge">{{ $stats['nowe_zgloszenia'] }} nowe</span>
        @endif
      </a>
      <a href="{{ route('admin.niskie_oceny') }}" class="card">
        <h3>Niskie oceny</h3>
        <p>Użytkownicy wymagający uwagi</p>
        @if($stats['niskie_oceny'] > 0)
        <span class="badge" style="background: #ffc107; color: #333;">{{ $stats['niskie_oceny'] }} poniżej 2.5</span>
        @endif
      </a>
      <a href="{{ route('admin.user_stats') }}" class="card">
        <h3>Statystyki Użytkowników</h3>
        <p>Szukaj po ID/Nicku i zarządzaj banami</p>
      </a>
      <a href="{{ route('admin.logs') }}" class="card">
        <h3>Dziennik Zdarzeń</h3>
        <p>Historia akcji administratorów</p>
      </a>
    </div>

    <div class="chart-container">
      <h3>Nowe ogłoszenia w ostatnim tygodniu</h3>
      <canvas id="ofertyChart"></canvas>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('ofertyChart').getContext('2d');
    const oferty = @json($ofertyData);
    const userzy = @json($usersStats);

    // Przygotowanie etykiet dat
    const labels = oferty.map(row => row.date);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
            label: 'Nowe ogłoszenia',
            data: oferty.map(row => row.aggregate),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
          },
          {
            label: 'Nowi użytkownicy',
            data: userzy.map(row => row.aggregate),
            borderColor: 'red',
            backgroundColor: 'rgba(255, 0, 0, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  </script>
</body>

</html>