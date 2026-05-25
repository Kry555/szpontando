<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/admin.css')


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
  <div id="modal_profil_standard"

    style="
        display:none;
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.7);
        z-index:9998;
     ">

    <div

      style="
            background:#fff;
            color:black;
            padding:25px;
            width:450px;
            margin:50px auto;
            position:relative;
            border-radius:12px;
            text-align:center;
        ">

      <button

        type="button"

        onclick="closeModal_profil()"

        style="
                position:absolute;
                top:15px;
                right:15px;
                border:none;
                background:none;
                font-size:22px;
                cursor:pointer;
            ">

        ✖

      </button>

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
                border:1px solid #eee;
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