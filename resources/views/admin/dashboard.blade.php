<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprzontando</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        h1 { color: #333; text-align: center; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; text-decoration: none; color: #333; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .card h3 { margin-top: 0; color: #007bff; }
        .card p { font-size: 14px; color: #666; }
        .badge { background: #dc3545; color: white; padding: 3px 8px; border-radius: 10px; font-size: 12px; font-weight: bold; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; margin-top: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
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
                datasets: [
                    { 
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
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    </script>
</body>
</html>