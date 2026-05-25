<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Sprzontando</title>
  <link rel="icon" href="{{ Vite::asset('resources/images/sprzontandoico.ico') }}" type="image/x-icon">
  @vite('resources/css/admin.css')

  <style>
    /* ========================= MODAL OVERLAY ========================= */

    #modal_profil_standard,
    #modal_history_detail {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .78);
      backdrop-filter: blur(7px);
      z-index: 9998;
      animation: fadeIn .2s ease;
    }

    /* ========================= MODAL BOX ========================= */

    .profil-modal-box,
    .history-modal-box {
      width: 95%;
      max-width: 520px;
      margin: 60px auto;
      background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
      border: 1px solid #334155;
      border-radius: 18px;
      padding: 24px;
      position: relative;
      color: #000000;
      box-shadow:
        0 0 25px rgba(0, 0, 0, .45),
        0 0 60px rgba(16, 185, 129, .08);
      animation: modalOpen .25s ease;
    }

    /* ========================= CLOSE BUTTON ========================= */

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

    /* ========================= AVATAR ========================= */

    .modal-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #10b981;
      margin-bottom: 15px;
      box-shadow: 0 0 20px rgba(16, 185, 129, .25);
    }

    /* ========================= TITLES ========================= */

    .modal-title {
      margin: 0 0 20px;
      font-size: 28px;
      color: #f8fafc;
    }

    .modal-subtitle {
      margin: 20px 0 12px;
      font-size: 18px;
      color: #10b981;
    }

    /* ========================= INFO BOX ========================= */

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
      line-height: 1.5;
    }

    .modal-info-box strong {
      color: #10b981;
    }

    /* ========================= TILES ========================= */

    .tiles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 12px;
    }

    .tile {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      border: 1px solid #334155;
      border-radius: 12px;
      padding: 14px;
      cursor: pointer;
      transition: .2s;
      color: #f8fafc;
      font-size: 14px;
      text-align: center;
      box-shadow: 0 0 10px rgba(0, 0, 0, .2);
    }

    .tile strong {
      display: block;
      margin-bottom: 8px;
      color: #10b981;
    }

    .tile:hover {
      transform: translateY(-3px);
      border-color: #10b981;
      box-shadow: 0 0 18px rgba(16, 185, 129, .2);
    }

    /* ========================= BUTTON ========================= */

    .modal-btn {
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      background: linear-gradient(135deg, #2563eb, #10b981);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      transition: .2s;
    }

    .modal-btn:hover {
      transform: translateY(-2px);
      filter: brightness(1.1);
    }

    /* ========================= REVIEW ========================= */

    .review-box {
      margin-top: 15px;
      padding: 14px;
      background: rgba(15, 23, 42, .7);
      border: 1px solid #334155;
      border-left: 4px solid #10b981;
      border-radius: 10px;
    }

    .review-user {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .review-user img {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
      border: 1px solid #334155;
    }

    .review-rating {
      color: #facc15;
      font-weight: 700;
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
    <h1>🚫 Zbanowane Oferty</h1>
    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
    @endif

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Właściciel</th>
          <th>Typ</th>
          <th>Adres</th>
          <th>Cena</th>
          <th>Data Zbanowania</th>
          <th>Opis</th>
          <th>Akcja</th>
        </tr>
      </thead>
      <tbody>
        @foreach($oferty as $o)
        <tr>
          <td>{{ $o->id_oferty }}</td>
          <td>
            <button class="profil-btn" onclick="openModal_profil(
                            '{{ $o->nick }}',
                            '{{ asset('images/profilowe/' . ($o->profilowe ?? 'default.png')) }}',
                            '{{ $o->imie }}',
                            '{{ $o->nazwisko }}',
                            '{{ $o->miasto ?? '' }}',
                            '{{ $o->email_kontaktowy ?? '' }}',
                            '{{ $o->ocena ?? '0' }}', 
                            '{{ $o->ostatnie_zlecenia ?? "[]" }}')"> {{-- Teraz pobieramy ostatnie zlecenia --}}
              <span>{{ $o->nick }}</span>
            </button>
          </td>
          <td>{{ $o->typ }}</td>
          <td>{{ $o->adres }}</td>
          <td>{{ $o->cena }} zł</td>
          <td>{{ $o->updated_at }}</td>
          <td>{{ Str::limit($o->opis, 50) }}</td>
          <td>
            <form action="{{ route('admin.odbanuj_oferte') }}" method="POST" style="margin:0;">
              @csrf
              <input type="hidden" name="id_oferty" value="{{ $o->id_oferty }}">
              <button type="submit" class="btn-unban">Odbanuj</button>
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

      <h3 class="modal-subtitle">
        Ostatnie zlecenia
      </h3>

      <div
        id="modal_profil_zlecenia_container"
        class="tiles-grid">

      </div>

    </div>

  </div>

  <!-- MODAL HISTORIA -->
  <div id="modal_history_detail">

    <div class="history-modal-box">

      <button
        type="button"
        onclick="closeModal_historyDetail()"
        class="modal-close">

        ✖

      </button>

      <h2
        id="history_detail_title"
        class="modal-title">

      </h2>

      <div id="history_detail_content"></div>

      <button
        onclick="closeModal_historyDetail()"
        class="modal-btn">

        Zamknij

      </button>

    </div>

  </div>

  <script>
    function openModal_profil(nick, profilowe, imie, nazwisko, miasto, email, ocena, zleceniaRaw) {
      document.getElementById('modal_profil_img').src = profilowe;
      document.getElementById('modal_profil_nick').textContent = nick;
      document.getElementById('modal_profil_imie').textContent = imie || 'Brak';
      document.getElementById('modal_profil_nazwisko').textContent = nazwisko || '';
      document.getElementById('modal_profil_miasto').textContent = miasto || 'Brak lokalizacji';
      document.getElementById('modal_profil_email').textContent = email || 'Brak kontaktu';
      document.getElementById('modal_profil_ocena').textContent = ocena || '0';

      const container = document.getElementById('modal_profil_zlecenia_container');
      container.innerHTML = '';

      let zleceniaArray = [];
      try {
        zleceniaArray = JSON.parse(zleceniaRaw);
      } catch (e) {
        zleceniaArray = [];
      }

      if (zleceniaArray.length > 0) {
        zleceniaArray.forEach(z => {
          const tile = document.createElement('div');
          tile.className = 'tile';
          tile.innerHTML = `<strong>Zlecenie</strong>${z.typ.replace('_', ' ')}`;
          tile.onclick = (e) => {
            e.stopPropagation();
            openModal_historyDetail(z);
          };
          container.appendChild(tile);
        });
      } else {
        container.innerHTML = '<p style="grid-column: 1/-1; color: #030303; font-style: italic;">Użytkownik nie posiada jeszcze historii współprac.</p>';
      }

      document.getElementById('modal_profil_standard').style.display = 'block';
    }

    function closeModal_profil() {
      document.getElementById('modal_profil_standard').style.display = 'none';
    }

    function openModal_historyDetail(data) { // Przyjmuje cały obiekt danych
      document.getElementById('history_detail_title').textContent = data.typ.replace('_', ' ');

      let reviewHtml = '';
      if (data.autor_nick) {
        reviewHtml = `
                    <div style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid orange;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <img src="/images/profilowe/${data.autor_foto}" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; border: 1px solid #ddd; object-fit: cover;">
                            <strong>${data.autor_nick}</strong>
                        </div>
                        <p style="margin: 5px 0; color: #d4af37; font-weight: bold;">Ocena: ${data.gwiazdki} / 5 ⭐</p>
                        <p style="margin: 5px 0; font-style: italic; color: #555;">"${data.opinia_tekst || 'Brak komentarza słownego'}"</p>
                    </div>
                `;
      } else {
        reviewHtml = `<p style="color: #070707; font-style: italic; margin-top: 15px;">To zlecenie nie otrzymało jeszcze opinii.</p>`;
      }

      document.getElementById('history_detail_content').innerHTML = `
                <div style="text-align: left; background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
                    <p style="margin: 5px 0;"><strong>Adres:</strong> ${data.adres || 'Brak'}</p>
                    <p style="margin: 5px 0;"><strong>Cena:</strong> ${data.cena || '0'} zł</p>
                    <p style="margin: 5px 0;"><strong>Ważne do:</strong> ${data.do_kiedy_wazne || 'Brak'}</p>
                    <p style="margin: 5px 0;"><strong>Opis:</strong> ${data.oferta_opis || 'Brak opisu'}</p>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <p><strong style="color:white">Opinia o tym użytkowniku:</strong></p>
                ${reviewHtml}
            `;
      document.getElementById('modal_history_detail').style.display = 'block';
    }

    function closeModal_historyDetail() {
      document.getElementById('modal_history_detail').style.display = 'none';
    }
  </script>
</body>

</html>