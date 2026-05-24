<div class="sidebar">
    <h2 style="color: white; text-align: center;">Szpontando Admin</h2>
    <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
    <a href="{{ route('admin.zgloszenia') }}">🚩 Zgłoszenia</a>
    <a href="{{ route('admin.zbanowane_oferty') }}">🚫 Zbanowane Oferty</a>
    <a href="{{ route('admin.niskie_oceny') }}">⚠️ Niskie Oceny</a>
    <a href="{{ route('admin.user_stats') }}">📊 Statystyki Użytkowników</a>
    <a href="{{ route('admin.logs') }}">📜 Dziennik Zdarzeń</a>
    <hr style="border-color: #444;">
    <a href="/">🌐 Strona główna</a>
</div>

<style>
    .sidebar { width: 250px; background: #333; height: 100vh; position: fixed; left: 0; top: 0; padding-top: 20px; z-index: 1000; }
    .sidebar a { padding: 15px; text-decoration: none; font-size: 16px; color: #ddd; display: block; transition: 0.3s; }
    .sidebar a:hover { background: #555; color: white; }
    body { margin-left: 260px; background-color: #f4f4f4; }
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
</style>