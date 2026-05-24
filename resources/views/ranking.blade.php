<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Sprzontando</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
    @vite('resources/css/stop_z_wypalaniem_gał.css')
    <style>
        .ranking-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .rank-item {
            display: flex;
            align-items: center;
            background: #2a2a2a;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #444;
        }

        .rank-number {
            font-size: 24px;
            font-weight: bold;
            min-width: 50px;
        }

        .rank-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 20px;
            border: 2px solid orange;
        }

        .rank-info {
            flex-grow: 1;
        }

        .rank-score {
            font-size: 20px;
            color: gold;
        }

        .top-1 {
            border: 2px solid gold;
            background: #3a321a;
        }

        .top-2 {
            border: 2px solid silver;
        }

        .top-3 {
            border: 2px solid #cd7f32;
        }
    </style>
</head>

<body>
    <div class="ranking-container">
        <h1 style="text-align: center;"> Najlepsi Wykonawcy</h1>

        @foreach($wykonawcy as $index => $w)
        @php
        $miejsce = $index + 1;
        $klasa = $miejsce <= 3 ? 'top-' .$miejsce : '' ;
            $ikona=match($miejsce) { 1=> '', 2 => '', 3 => '', default => '#' . $miejsce };
            @endphp

            <div class="rank-item {{ $klasa }}">
                <div class="rank-number">{{ $ikona }}</div>
                <img src="{{ asset('images/profilowe/' . $w->profilowe) }}" class="rank-photo">
                <div class="rank-info">
                    <h3 style="margin: 0;">{{ $w->nick }} @if($miejsce == 1)  @endif</h3>
                    <small>{{ $w->miasto ?? 'Brak lokalizacji' }}</small>
                </div>

                <div class=" rank-score">
                    {{ $w->ocena }} / 5 ⭐
                </div>
            </div>
            @endforeach

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('main') }}"><button>Wróć na strone główną </button></a>
            </div>
    </div>
</body>

</html>