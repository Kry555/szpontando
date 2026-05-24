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

                    <!-- DUŻE ZDJĘCIA -->
                    <div class="oferta-zdjecia-duze my-4 d-flex flex-wrap gap-3 justify-content-center">

                        @if($oferta->zdjecie_1)

                            <img
                                src="{{ asset('images/oferty/' . $oferta->zdjecie_1) }}"
                                class="img-fluid rounded border border-warning shadow"
                                style="
                                    max-height:350px;
                                    width:auto;
                                    object-fit:contain;
                                "
                                alt="zdjęcie 1"
                            >

                        @endif

                        @if($oferta->zdjecie_2)

                            <img
                                src="{{ asset('images/oferty/' . $oferta->zdjecie_2) }}"
                                class="img-fluid rounded border border-warning shadow"
                                style="
                                    max-height:350px;
                                    width:auto;
                                    object-fit:contain;
                                "
                                alt="zdjęcie 2"
                            >

                        @endif
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

<script>

    function openModal_zglos(ofertaId) {

        document.getElementById('modal_zglos').style.display = 'block';

        document.getElementById('modal_oferta_id').value = ofertaId;
    }

    function closeModal_zglos() {

        document.getElementById('modal_zglos').style.display = 'none';
    }

    window.onclick = function(event) {

        const modal = document.getElementById('modal_zglos');

        if (event.target == modal) {

            closeModal_zglos();
        }
    }

</script>

</body>
</html>