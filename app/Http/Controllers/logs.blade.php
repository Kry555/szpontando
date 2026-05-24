<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dziennik Zdarzeń Admina</title>
    <style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } tr:nth-child(even) { background: #f9f9f9; }</style>
</head>
<body>
    @include('admin.sidebar')

    <div class="container">
    <h1>Dziennik Zdarzeń (Admin Logs)</h1>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Administrator</th>
                <th>Akcja</th>
                <th>Szczegóły</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logi as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->admin_nick }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->details }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>
</html>