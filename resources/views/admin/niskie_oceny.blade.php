<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/admin.css')
  <style>
    /* ========================= MODAL ========================= */

    #modal_profil_standard {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .78);
      backdrop-filter: blur(6px);
      z-index: 9998;
      animation: fadeIn .2s ease;
    }

    .profil-modal-box {
      width: 95%;
      max-width: 480px;
      margin: 70px auto;
      background: linear-gradient(180deg, #0f172a, #111827);
      border: 1px solid #334155;
      border-radius: 18px;
      padding: 24px;
      position: relative;
      color: #f8fafc;
      box-shadow:
        0 0 25px rgba(0, 0, 0, .45),
        0 0 60px rgba(16, 185, 129, .08);
      animation: modalOpen .25s ease;
    }

    .modal-close {
      position: absolute;
      top: 14px;
      right: 16px;
      border: none;
      background: transparent;
      color: #94a3b8;
      font-size: 22px;
      cursor: pointer;
      transition: .2s;
    }

    .modal-close:hover {
      color: #ef4444;
      transform: scale(1.1);
    }

    .modal-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #10b981;
      margin-bottom: 15px;
      box-shadow: 0 0 20px rgba(16, 185, 129, .25);
    }

    .modal-title {
      margin: 0 0 20px;
      font-size: 28px;
      color: #f8fafc;
    }

    .modal-info-box {
      background: rgba(15, 23, 42, .7);
      border: 1px solid #334155;
      border-radius: 12px;
      padding: 16px;
      text-align: left;
    }

    .modal-info-box p {
      margin: 8px 0;
      color: #e2e8f0;
    }

    .modal-info-box strong {
      color: #10b981;
    }

    /* ========================= BAN FORM ========================= */

    .ban-form {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .ban-input {
      background: #0f172a;
      border: 1px solid #334155;
      color: #f8fafc;
      border-radius: 10px;
      padding: 10px 12px;
      outline: none;
      transition: .2s;
    }

    .ban-input:focus {
      border-color: #10b981;
      box-shadow: 0 0 12px rgba(16, 185, 129, .15);
    }

    .days-input {
      width: 100px;
    }

    .reason-input {
      min-width: 180px;
    }

    .ban-btn {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 10px 16px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
    }

    .ban-btn:hover {
      transform: translateY(-2px);
      filter: brightness(1.08);
    }

    .unban-btn {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 10px 16px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
    }

    .unban-btn:hover {
      transform: translateY(-2px);
      filter: brightness(1.08);
    }

    /* ========================= ANIMATIONS ========================= */

    @keyframes modalOpen {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(.97);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>

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
          <td>
            <button class="profil-btn" onclick="openModal_profil(
                        '{{ $u->nick }}',
                        '{{ asset('images/profilowe/' . $u->profilowe) }}',
                        '{{ $u->imie }}',
                        '{{ $u->nazwisko }}',
                        '{{ $u->miasto }}',
                        '{{ $u->email_kontaktowy }}',
                        '{{ $u->ocena ?? '0' }}')">

              <span>{{ $u->nick }}</span>
            </button>
          </td>
          <td>{{ $u->ocena }}</td>
          <td>{{ $u->aktywny ? 'Aktywny' : 'Zablokowany' }}</td>
          <td>
            @if($u->aktywny)
            <form action="{{ route('admin.ban_user') }}" method="POST">
              @csrf
              <input type="hidden" name="id_user" value="{{ $u->id }}">
              <input type="number" name="dni" placeholder="Liczba dni" required min="1" class="ban-input days-input" style="width: 80px;">
              <input type="text" name="powod" placeholder="Powód" class="ban-input reason-input" required>
              <button type="submit" class="ban-form">Nałóż ban</button>
            </form>
            @else
            <form action="{{ route('admin.unban_user') }}" method="POST">
              @csrf
              <input type="hidden" name="id_user" value="{{ $u->id }}">
              <button type="submit" class="unban-btn" style="background-color: #28a745; color: white; border: none; padding: 5px; cursor: pointer;">
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

  <!-- MODAL PROFIL -->
  <div id="modal_profil_standard">

    <div class="profil-modal-box">

      <button
        type="button"
        onclick="closeModal_profil()"
        class="modal-close">

        ✖

      </button>

      <div style="text-align:center;">

        <img
          id="modal_profil_img"
          src=""
          class="modal-avatar">

        <h2
          id="modal_profil_nick"
          class="modal-title">

        </h2>

      </div>

      <div class="modal-info-box">

        <p>
          <strong>Imię i nazwisko:</strong>
          <span id="modal_profil_imie"></span>
          <span id="modal_profil_nazwisko"></span>
        </p>

        <p>
          <strong>Miasto:</strong>
          <span id="modal_profil_miasto"></span>
        </p>

        <p>
          <strong>Email:</strong>
          <span id="modal_profil_email"></span>
        </p>

        <p>
          <strong>Średnia ocena:</strong>
          <span id="modal_profil_ocena"></span> / 5 ⭐
        </p>

      </div>

    </div>

  </div>

  <script>
    function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena) {
      document.getElementById('modal_profil_img').src = profilowe;
      document.getElementById('modal_profil_nick').textContent = nick;
      document.getElementById('modal_profil_imie').textContent = imie || 'Brak';
      document.getElementById('modal_profil_nazwisko').textContent = nazwisko || '';
      document.getElementById('modal_profil_miasto').textContent = miasto || 'Brak lokalizacji';
      document.getElementById('modal_profil_email').textContent = email || 'Brak kontaktu';
      document.getElementById('modal_profil_ocena').textContent = ocena || '0';
      document.getElementById('modal_profil_standard').style.display = 'block';
    }

    function closeModal_profil() {
      document.getElementById('modal_profil_standard').style.display = 'none';
    }
  </script>
</body>

</html>