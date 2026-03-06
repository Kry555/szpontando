<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/sign_up.css')
</head>

<body>
    <h1>zrub se konto</h1>
    @if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="text" name="nick" placeholder="Nicke" value="{{ old('nick') }}" required><br>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required><br>
        <input type="password" name="password" placeholder="Hasło" required><br>
        <input type="password" name="password_confirmation" placeholder="Powtórz hasło" required><br>

        <div class="men">
            <input type="radio" id="menCheck" name="gender" value="men" {{ old('gender') == 'men' ? 'checked' : '' }}>
            <label for="menCheck" class="custom-checkbox"></label>
            <span>Men</span>
        </div>

        <div class="women">
            <input type="radio" id="womenCheck" name="gender" value="women" {{ old('gender') == 'women' ? 'checked' : '' }}>
            <label for="womenCheck" class="custom-checkbox"></label>
            <span>Women</span>
        </div>

        <div class="slup">
            <input type="radio" id="slupCheck" name="gender" value="slup" {{ old('gender') == 'slup' ? 'checked' : '' }}>
            <label for="slupCheck" class="custom-checkbox"></label>
            <span>Słup elektryczny</span>
        </div>

        <button type="submit">Zmien profil</button>
    </form>

    <a href="{{ route('login') }}">
        <button type="button">Sign_in</button>
    </a>

    <script>
        document.getElementById("captchaCheck").addEventListener("change", function() {

            const checkbox = this;

            if (checkbox.checked) {

                setTimeout(() => {
                    checkbox.classList.add("checked");
                }, 1000);

            } else {
                checkbox.classList.remove("checked");
            }

        });
    </script>
</body>

</html>