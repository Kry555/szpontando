<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zgłoszenia nadużyć</title>
    <style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } .btn-ban { color: red; } .btn-ok { color: green; }</style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Zgłoszone ogłoszenia</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <table>
        <thead>
            <tr>
                <th>ID Zgłoszenia</th>
                <th>ID Oferty</th>
                <th>Powód</th>
                <th>Opis oferty</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zgloszenia as $z)
            <tr>
                <td>{{ $z->id_zgloszenia }}</td>
                <td>{{ $z->id_oferty }}</td>
                <td>{{ $z->powod }}</td>
                <td>{{ Str::limit($z->opis, 100) }}</td>
                <td>
                    <form action="{{ route('admin.rozpatrz_zgloszenie') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="id_zgloszenia" value="{{ $z->id_zgloszenia }}">
                        <input type="hidden" name="decyzja" value="ban">
                        <button type="submit" class="btn-ban">Banuj Ofertę</button>
                    </form>
                    
                    <form action="{{ route('admin.rozpatrz_zgloszenie') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="id_zgloszenia" value="{{ $z->id_zgloszenia }}">
                        <input type="hidden" name="decyzja" value="ok">
                        <button type="submit" class="btn-ok">Jest OK</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>
</html>