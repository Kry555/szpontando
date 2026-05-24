<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Użytkownicy o niskich ocenach</title>
    <style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }</style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Użytkownicy ze średnią oceną poniżej 2.5</h1>
    
    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nick</th>
                <th>Ocena</th>
                <th>Status</th>
                <th>Akcja (Banuj)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($uzytkownicy as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->nick }}</td>
                <td>{{ $u->ocena }}</td>
                <td>{{ $u->aktywny ? 'Aktywny' : 'Zablokowany' }}</td>
                <td>
                    @if($u->aktywny)
                        <form action="{{ route('admin.ban_user') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $u->id }}">
                            <input type="number" name="dni" placeholder="Liczba dni" required min="1" style="width: 80px;">
                            <input type="text" name="powod" placeholder="Powód" required>
                            <button type="submit">Nałóż ban</button>
                        </form>
                    @else
                        <form action="{{ route('admin.unban_user') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $u->id }}">
                            <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 5px; cursor: pointer;">
                                Odbanuj
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>
</html>