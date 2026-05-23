<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Statystyki Użytkownika</title>
    <style>.card { border: 1px solid #ccc; padding: 20px; max-width: 500px; margin-top: 20px; }</style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Szukaj użytkownika</h1>

    <form action="{{ route('admin.user_stats') }}" method="GET">
        <input type="text" name="search" placeholder="Nick lub ID użytkownika" value="{{ request('search') }}" required>
        <button type="submit">Szukaj</button>
    </form>

    @if(isset($user))
        <div class="card">
            <h2>Statystyki dla: {{ $user->nick }} (ID: {{ $user->id }})</h2>
            <p><strong>Konto od:</strong> {{ $user->created_at }}</p>
            <p><strong>Aktualna ocena:</strong> {{ $user->ocena ?? 'Brak ocen' }} / 5</p>
            <hr>
            <p><strong>Ilość ofert (wszystkich):</strong> {{ $iloscOfert }}</p>
            <p><strong>Oferty zaakceptowane:</strong> {{ $zaakceptowaneOferty }}</p>
            <p><strong>Skuteczność:</strong> 
                @if($iloscOfert > 0)
                    {{ round(($zaakceptowaneOferty / $iloscOfert) * 100, 2) }}%
                @else
                    0%
                @endif
            </p>
            <p><strong>Status:</strong> {{ $user->aktywny ? 'Aktywny' : 'Zablokowany' }}</p>
            @if($user->zbanowany_do)
                <p style="color: red;"><strong>Zbanowany do:</strong> {{ $user->zbanowany_do }}</p>
            @endif

            @if($user->aktywny)
                <form action="{{ route('admin.ban_user') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <input type="number" name="dni" placeholder="Dni bana" required min="1">
                    <input type="text" name="powod" placeholder="Powód bana" required>
                    <button type="submit">Banuj czasowo</button>
                </form>
            @else
                <form action="{{ route('admin.unban_user') }}" method="POST" style="margin-top: 10px;">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
                        Odbanuj użytkownika
                    </button>
                </form>
            @endif
        </div>
    @elseif(request('search'))
        <p>Nie znaleziono użytkownika o podanym nicku lub ID.</p>
    @endif

    @if(isset($userLogs))
        <hr>
        <h3>Historia logów administracyjnych dla tego użytkownika</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="border: 1px solid #ccc; padding: 8px;">Data</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Admin</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Akcja</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Szczegóły</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userLogs as $log)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->created_at }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->admin_nick }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->action }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $log->details }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; padding: 10px;">Brak wpisów w dzienniku dla tego użytkownika.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
    </div>
</body>
</html>