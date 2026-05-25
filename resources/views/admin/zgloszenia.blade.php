<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">

  @vite('resources/css/admin.css')
  <style>
    /* ========================= MODAL BACKGROUND ========================= */

    #modal_profil_standard {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(6px);
      z-index: 9998;
      animation: fadeIn .2s ease;
    }

    /* ========================= MODAL BOX ========================= */

    .profil-modal-box {
      width: 95%;
      max-width: 480px;
      margin: 70px auto;
      background: linear-gradient(180deg, #0f172a, #111827);
      border: 1px solid #334155;
      border-radius: 18px;
      padding: 25px;
      color: #f8fafc;
      position: relative;
      box-shadow:
        0 0 30px rgba(0, 0, 0, .45),
        0 0 60px rgba(16, 185, 129, .08);
      animation: modalOpen .25s ease;
    }

    /* ========================= CLOSE BUTTON ========================= */

    .profil-close {
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

    .profil-close:hover {
      color: #ef4444;
      transform: scale(1.1);
    }

    /* ========================= AVATAR ========================= */

    .profil-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #10b981;
      margin-bottom: 15px;
      box-shadow: 0 0 20px rgba(16, 185, 129, .25);
    }

    /* ========================= TITLE ========================= */

    .profil-title {
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 28px;
      color: #f8fafc;
    }

    /* ========================= INFO BOX ========================= */

    .profil-info {
      background: rgba(15, 23, 42, .7);
      border: 1px solid #334155;
      border-radius: 12px;
      padding: 18px;
      text-align: left;
    }

    .profil-info p {
      margin-bottom: 12px;
      color: #e2e8f0;
      font-size: 15px;
    }

    .profil-info strong {
      color: #10b981;
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
    <h1>Zgłoszone ogłoszenia</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <table>
      <thead>
        <tr>
          <th>ID Zgłoszenia</th>
          <th>ID Oferty</th>
          <th>Autor Oferty</th>
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
          <td>
            <button class="profil-btn" onclick="openModal_profil(
                        '{{ $z->nick }}',
                        '{{ asset('images/profilowe/' . $z->profilowe) }}',
                        '{{ $z->imie }}',
                        '{{ $z->nazwisko }}',
                        '{{ $z->miasto }}',
                        '{{ $z->email_kontaktowy }}',
                        '{{ $z->ocena ?? '0' }}')">
              <span>{{ $z->nick }}</span>
            </button>
          </td>
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

  <!-- MODAL PROFIL -->
  <div id="modal_profil_standard">

    <div class="profil-modal-box">

      <button
        type="button"
        onclick="closeModal_profil()"
        class="profil-close">

        ✖

      </button>

      <div style="text-align:center;">

        <img
          id="modal_profil_img"
          src=""
          class="profil-avatar">

        <h2
          id="modal_profil_nick"
          class="profil-title">

        </h2>

      </div>

      <div class="profil-info">

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