<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <title>
        Szczegóły oferty - {{ $oferta->typ }}
    </title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>

        .tiles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
            margin-top: 15px;
            padding: 5px;
        }

        .tile {
            background: #2a2a2a;
            color: white;
            border: 1px solid orange;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 13px;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .tile:hover {
            transform: translateY(-3px);
            background: #333;
            box-shadow: 0 4px 8px rgba(255, 165, 0, 0.3);
        }

        .tile strong {
            display: block;
            color: orange;
            margin-bottom: 5px;
            font-size: 12px;
        }

    </style>
</head>

<body class="bg-light">

<div class="container py-5">

    <a href="{{ route('main') }}" class="btn btn-secondary mb-4">
        ← Powrót do ogłoszeń
    </a>

    <div class="row">

        <!-- LEWA STRONA -->
        <div class="col-md-8">

            <!-- OFERTA -->
            <div class="card mb-4 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <h1 class="h2">
                            {{ ucfirst(str_replace('_', ' ', $oferta->typ)) }}
                        </h1>

                        <span class="badge {{ $oferta->status == 'aktywna' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ strtoupper($oferta->status) }}
                        </span>

                    </div>

                    <p class="text-muted">
                        Dodano: {{ $oferta->created_at }}
                    </p>

                    <hr>

                    <h5 class="text-primary">
                        Lokalizacja: {{ $oferta->adres }}
                    </h5>

                    <h4 class="fw-bold">
                        Cena:
                        <span class="text-success">
                            {{ $oferta->cena }} PLN
                        </span>
                    </h4>

                    <p class="mt-3" style="white-space: pre-wrap;">
                        {{ $oferta->opis }}
                    </p>

                    <p class="text-danger">
                        Ważne do:
                        <strong>{{ $oferta->do_kiedy_wazne }}</strong>
                    </p>

                    <div class="mt-4">

                        @auth

                            @if($juz_zgloszony)

                                <button class="btn btn-secondary w-100 py-2 fw-bold" disabled>
                                    Już zgłosiłeś się do tej oferty
                                </button>

                            @else

                                <button
                                    class="btn btn-success w-100 py-2 fw-bold"
                                    onclick="openModal_zglos({{ $oferta->id_oferty }})"
                                    {{ $oferta->status !== 'aktywna' ? 'disabled' : '' }}
                                >

                                    {{ $oferta->status === 'aktywna'
                                        ? 'Zgłoś się do tej oferty'
                                        : 'Zgłoszenia są już zamknięte'
                                    }}

                                </button>

                            @endif

                        @else

                            <button class="btn btn-outline-secondary w-100 py-2 fw-bold" disabled>
                                Zaloguj się aby się zgłosić
                            </button>

                        @endauth

                    </div>

                </div>

            </div>

            <!-- ZGŁOSZENIA -->
            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Osoby zainteresowane tym zleceniem
                        ({{ $zgloszenia->count() }})
                    </h5>

                </div>

                <ul class="list-group list-group-flush"
                    style="max-height:400px; overflow-y:auto;">

                    @forelse($zgloszenia as $z)

                        <li class="list-group-item d-flex align-items-center p-3">

                            <img
                                src="{{ asset('images/profilowe/' . ($z->profilowe ?? 'default.jpg')) }}"
                                class="rounded-circle me-3"
                                width="50"
                                height="50"
                                alt="avatar"
                            >

                            <div class="flex-grow-1">

                                <h6 class="mb-0">
                                    {{ $z->nick }}
                                    ({{ $z->imie }} {{ $z->nazwisko }})
                                </h6>

                                <small class="text-muted">
                                    Miasto: {{ $z->miasto }}
                                    |
                                    Ocena: {{ $z->ocena }}/5
                                </small>

                                @if($z->wiadomosc)

                                    <p class="mb-0 mt-1 small fst-italic">
                                        "{{ $z->wiadomosc }}"
                                    </p>

                                @endif

                            </div>

                            <span class="badge bg-info text-dark">
                                {{ $z->status }}
                            </span>

                        </li>

                    @empty

                        <li class="list-group-item text-center py-4 text-muted">
                            Nikt jeszcze się nie zgłosił.
                        </li>

                    @endforelse

                </ul>

            </div>

        </div>

        <!-- PRAWA STRONA -->
        <div class="col-md-4">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white text-center">
                    Właściciel oferty
                </div>

                <div class="card-body text-center">

                    <img
                        src="{{ asset('images/profilowe/' . ($oferta->owner_foto ?? 'default.jpg')) }}"
                        class="rounded-circle mb-3 border"
                        width="120"
                        height="120"
                        alt="owner"
                    >

                    <h3>
                        {{ $oferta->owner_nick }}
                    </h3>

                    <p class="mb-1 text-muted">
                        {{ $oferta->owner_imie }}
                        {{ $oferta->owner_nazwisko }}
                    </p>

                    <div class="mb-3">

                        <span class="badge bg-warning text-dark">
                            Ocena: {{ $oferta->owner_ocena }}/5
                        </span>

                    </div>

                    <ul class="list-group list-group-flush text-start small">

                        <li class="list-group-item">
                            <strong>Miasto:</strong>
                            {{ $oferta->owner_miasto }}
                        </li>

                        <li class="list-group-item">

                            <strong>Email:</strong>

                            @auth
                                {{ $oferta->owner_email ?? 'Brak danych' }}
                            @else
                                Zaloguj się aby zobaczyć
                            @endauth

                        </li>

                        <li class="list-group-item">

                            <strong>Płeć:</strong>

                            @switch($oferta->owner_sex)

                                @case('men')
                                    Mężczyzna
                                    @break

                                @case('women')
                                    Kobieta
                                    @break

                                @case('slup')
                                    Słup elektryczny
                                    @break

                                @default
                                    Brak danych

                            @endswitch

                        </li>

                    </ul>

                </div>

                <div class="card-footer bg-white border-0">



                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL ZGŁOSZENIA -->
<div id="modal_zglos"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000;">

    <div id="modal_content"
         style="background:#fff; color:black; padding:20px; width:400px; margin:100px auto; position:relative; border-radius:8px;">

        <h2 class="h4 mb-3">
            Zgłoś chęć wykonania
        </h2>

        <form method="POST"
              action="{{ route('oferta.wybierz') }}">

            @csrf

            <input type="hidden"
                   name="oferta_id"
                   id="modal_oferta_id">

            <label class="form-label">
                Wiadomość:
            </label>

            <textarea
                name="wiadomosc"
                required
                class="form-control"
                style="height:120px;"
            ></textarea>

            <div class="mt-3 d-flex gap-2">

                <button type="submit"
                        class="btn btn-primary flex-grow-1">

                    Wyślij

                </button>

                <button type="button"
                        class="btn btn-light"
                        onclick="closeModal_zglos()">

                    Anuluj

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL PROFIL -->
<div id="modal_owner_profil"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9998;">

    <div style="background:#fff; color:black; padding:25px; width:450px; margin:50px auto; position:relative; border-radius:12px; text-align:center;">

        <button type="button"
                onclick="closeModal_owner_profil()"
                style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">

            ✖

        </button>

        <img id="owner_profil_img"
             src=""
             style="width:110px; height:110px; border-radius:50%; margin-bottom:15px; border:3px solid orange; object-fit:cover;">

        <h2 id="owner_profil_nick"></h2>

        <div style="text-align:left; background:#fdfdfd; border:1px solid #eee; padding:15px; border-radius:8px; margin-bottom:15px;">

            <p>
                <strong>Imię:</strong>
                <span id="owner_profil_imie"></span>
                <span id="owner_profil_nazwisko"></span>
            </p>

            <p>
                <strong>Miasto:</strong>
                <span id="owner_profil_miasto"></span>
            </p>

            <p>
                <strong>Email:</strong>
                <span id="owner_profil_email"></span>
            </p>

            <p>
                <strong>Ocena:</strong>

                <span id="owner_profil_ocena"
                      style="color:#d4af37; font-weight:bold;"></span>

                / 5 ⭐
            </p>

        </div>

        <hr>

        <h3 style="font-size:1.1em;">
            Oferty stworzone
        </h3>

        <div id="owner_created_offers_container"
             class="tiles-grid"></div>

        <hr>

        <h3 style="font-size:1.1em;">
            Zlecenia wykonane
        </h3>

        <div id="owner_completed_jobs_container"
             class="tiles-grid"></div>

    </div>

</div>

<!-- MODAL HISTORIA -->
<div id="modal_history_detail"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:9999;">

    <div style="background:#fff; color:black; padding:25px; width:380px; margin:130px auto; position:relative; border-radius:12px;">

        <button type="button"
                onclick="closeModal_historyDetail()"
                style="position:absolute; top:15px; right:15px; border:none; background:none; font-size:22px; cursor:pointer;">

            ✖

        </button>

        <h2 id="history_detail_title"
            style="color:orange;"></h2>

        <div id="history_detail_content"></div>

    </div>

</div>

<script>

    function openModal_zglos(ofertaId) {

        document.getElementById('modal_zglos').style.display = 'block';

        document.getElementById('modal_oferta_id').value = ofertaId;
    }

    function closeModal_zglos() {

        document.getElementById('modal_zglos').style.display = 'none';
    }

    function openModal_owner_profil(
        nick,
        profilowe,
        imie,
        nazwisko,
        miasto,
        email,
        ocena,
        createdOffers,
        completedJobs
    ) {

        document.getElementById('owner_profil_img').src = profilowe;

        document.getElementById('owner_profil_nick').textContent = nick;

        document.getElementById('owner_profil_imie').textContent = imie || 'Brak';

        document.getElementById('owner_profil_nazwisko').textContent = nazwisko || '';

        document.getElementById('owner_profil_miasto').textContent = miasto || 'Brak';

        document.getElementById('owner_profil_email').textContent = email || 'Brak';

        document.getElementById('owner_profil_ocena').textContent = ocena || '0';

        // OFERTY
        const createdContainer = document.getElementById('owner_created_offers_container');

        createdContainer.innerHTML = '';

        if (createdOffers && createdOffers.length > 0) {

            createdOffers.forEach(offer => {

                const tile = document.createElement('div');

                tile.className = 'tile';

                tile.innerHTML = `
                    <strong>Oferta</strong>
                    ${offer.typ ? offer.typ.replace(/_/g, ' ') : 'Brak'}
                `;

                tile.onclick = () => openModal_historyDetail(offer);

                createdContainer.appendChild(tile);
            });

        } else {

            createdContainer.innerHTML =
                '<p style="grid-column:1/-1;color:#999;">Brak ofert.</p>';
        }

        // ZLECENIA
        const completedContainer = document.getElementById('owner_completed_jobs_container');

        completedContainer.innerHTML = '';

        if (completedJobs && completedJobs.length > 0) {

            completedJobs.forEach(job => {

                const tile = document.createElement('div');

                tile.className = 'tile';

                tile.innerHTML = `
                    <strong>Zlecenie</strong>
                    ${job.typ ? job.typ.replace(/_/g, ' ') : 'Brak'}
                `;

                tile.onclick = () => openModal_historyDetail(job);

                completedContainer.appendChild(tile);
            });

        } else {

            completedContainer.innerHTML =
                '<p style="grid-column:1/-1;color:#999;">Brak zleceń.</p>';
        }

        document.getElementById('modal_owner_profil').style.display = 'block';
    }

    function closeModal_owner_profil() {

        document.getElementById('modal_owner_profil').style.display = 'none';
    }

    function openModal_historyDetail(data) {

        document.getElementById('history_detail_title').textContent =
            data.typ
                ? data.typ.replace(/_/g, ' ')
                : 'Brak danych';

        document.getElementById('history_detail_content').innerHTML = `

            <p>
                <strong>Adres:</strong>
                ${data.adres || 'Brak'}
            </p>

            <p>
                <strong>Cena:</strong>
                ${data.cena || '0'} zł
            </p>

            <p>
                <strong>Opis:</strong>
                ${data.oferta_opis || 'Brak'}
            </p>

            <p>
                <strong>Ważne do:</strong>
                ${data.do_kiedy_wazne || 'Brak'}
            </p>
        `;

        document.getElementById('modal_history_detail').style.display = 'block';
    }

    function closeModal_historyDetail() {

        document.getElementById('modal_history_detail').style.display = 'none';
    }

    // KLIKNIĘCIE W TŁO
    window.onclick = function(event) {

        const modalZglos = document.getElementById('modal_zglos');

        const modalOwner = document.getElementById('modal_owner_profil');

        const modalHistory = document.getElementById('modal_history_detail');

        if (event.target == modalZglos) {
            closeModal_zglos();
        }

        if (event.target == modalOwner) {
            closeModal_owner_profil();
        }

        if (event.target == modalHistory) {
            closeModal_historyDetail();
        }
    }

</script>

</body>
</html>