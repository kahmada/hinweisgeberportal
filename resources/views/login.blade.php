<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Anmeldung - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 13px; font-weight: 700; color: #005FB8; letter-spacing: -0.3px; margin-bottom: 1rem;">Hinweisgeberportal</div>
                <div class="auth-title">Admin-Anmeldung</div>
                <div class="auth-subtitle">Zugang für interne Bearbeiter</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">E-Mail-Adresse</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Passwort</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 0.5rem;">
                    Anmelden
                </button>
            </form>

            <hr class="divider">
            <div style="text-align: center; font-size: 13px; color: #6b7280;">
                Hinweisgeber? <a href="/" class="link">Hinweis einreichen</a>
            </div>
        </div>
    </div>
</body>
</html>
