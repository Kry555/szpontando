<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/admin.css')

  <style>
    /* ========================= MODAL ========================= */

    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .78);
      backdrop-filter: blur(6px);
      z-index: 9998;
      animation: fadeIn .2s ease;
    }

    .modal_content {
      width: 95%;
      max-width: 500px;
      margin: 70px auto;
      background: linear-gradient(180deg, #0f172a, #111827);
      border: 1px solid #334155;
      border-radius: 18px;
      padding: 24px;
      position: relative;
      color: #f8fafc;
      text-align: center;
      box-shadow:
        0 0 25px rgba(0, 0, 0, .45),
        0 0 60px rgba(16, 185, 129, .08);
      animation: modalOpen .25s ease;
    }

    /* ========================= CLOSE BUTTON ========================= */

    .modal_close {
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

    .modal_close:hover {
      color: #ef4444;
      transform: scale(1.1);
    }

    /* ========================= AVATAR ========================= */

    .modal_avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #10b981;
      margin-bottom: 15px;
      box-shadow: 0 0 20px rgba(16, 185, 129, .25);
    }

    /* ========================= TITLE ========================= */

    .modal_title {
      margin: 0 0 20px;
      font-size: 28px;
      color: #f8fafc;
    }

    /* ========================= INFO BOX ========================= */

    .modal_info {
      text-align: left;
      background: rgba(15, 23, 42, .7);
      border: 1px solid #334155;
      padding: 16px;
      border-radius: 12px;
    }

    .modal_info p {
      margin: 8px 0;
      color: #e2e8f0;
      line-height: 1.5;
    }

    .modal_info strong {
      color: #10b981;
    }

    /* ========================= PROFILE BUTTON ========================= */

    .profil-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #1e293b, #0f172a);
      border: 1px solid #334155;
      color: #f8fafc;
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
      transition: .2s;
    }

    .profil-btn img {
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid #334155;
    }

    .profil-btn:hover {
      transform: translateY(-2px);
      border-color: #10b981;
      box-shadow: 0 0 14px rgba(16, 185, 129, .18);
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

          <!-- DATA -->
          <td>
            {{ $log->created_at }}
          </td>

          <!-- ADMIN -->
          <td>

            <button class="profil-btn"

              onclick="openModal_profil(
                            '{{ $log->admin_nick }}',
                            '{{ asset('images/profilowe/' . ($log->admin_profilowe ?: 'default.png')) }}',
                            '{{ $log->admin_imie }}',
                            '{{ $log->admin_nazwisko }}',
                            '{{ $log->admin_miasto }}',
                            '',
                            ''
                        )">

              <img
                src="{{ asset('images/profilowe/' . ($log->admin_profilowe ?: 'default.png')) }}"
                alt="P"
                width="25"
                height="25">

              <span>
                {{ $log->admin_nick }}
              </span>

            </button>

          </td>

          <!-- AKCJA -->
          <td>
            {{ $log->action }}
          </td>

          <!-- SZCZEGÓŁY -->
          <td>

            @php

            $details = e($log->details);

            if($log->target_id){

            $button = '
            <button class="profil-btn"

              onclick="openModal_profil(
                                    \'' . e($log->target_nick) . '\',
                                    \'' . asset('images/profilowe/' . ($log->profilowe ?: 'default.png')) . '\',
                                    \'' . e($log->imie) . '\',
                                    \'' . e($log->nazwisko) . '\',
                                    \'' . e($log->miasto) . '\',
                                    \'' . e($log->email) . '\',
                                    \'' . e($log->ocena ?? '0') . '\'
                                )">

              <img
                src="' . asset('images/profilowe/' . ($log->profilowe ?: 'default.png')) . '"
                width="25"
                height="25">

              <span>' . e($log->target_nick) . '</span>

            </button>
            ';

            $details = preg_replace(
            '/Użytkownik ID:\s*\d+/',
            $button,
            $details
            );
            }

            @endphp

            {!! $details !!}

          </td>

        </tr>

        @endforeach

      </tbody>

    </table>

  </div>

  <!-- MODAL PROFIL -->
  <div id="modal_profil_standard" class=modal>

    <div class="modal_content">
      <button type="button" onclick="closeModal_profil()" style="position:absolute;top:15px;right:15px;border:none;background:none;font-size:22px;cursor:pointer;">✖</button>

      <img

        id="modal_profil_img"

        src=""

        style="
                width:110px;
                height:110px;
                border-radius:50%;
                margin-bottom:15px;
                border:3px solid orange;
                object-fit:cover;
            ">

      <h2 id="modal_profil_nick"></h2>

      <div

        style="
                text-align:left;
                background:#fdfdfd;
                border:1px solid #1a1818;
                padding:15px;
                border-radius:8px;
            ">

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
          <strong>Ocena:</strong>

          <span id="modal_profil_ocena"></span>
        </p>

      </div>

    </div>

  </div>

  <script>
    function openModal_profil(
      nick,
      profilowe,
      imie,
      nazwisko,
      miasto,
      email,
      ocena
    ) {

      document.getElementById('modal_profil_img').src = profilowe;

      document.getElementById('modal_profil_nick').textContent = nick;

      document.getElementById('modal_profil_imie').textContent =
        imie || 'Brak';

      document.getElementById('modal_profil_nazwisko').textContent =
        nazwisko || '';

      document.getElementById('modal_profil_miasto').textContent =
        miasto || 'Brak lokalizacji';

      document.getElementById('modal_profil_email').textContent =
        email || 'Brak kontaktu';

      document.getElementById('modal_profil_ocena').textContent =
        ocena || '0';

      document.getElementById('modal_profil_standard').style.display =
        'block';
    }

    function closeModal_profil() {

      document.getElementById('modal_profil_standard').style.display =
        'none';
    }
  </script>

</body>

</html>