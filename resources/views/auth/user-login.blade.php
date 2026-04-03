<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 13px; font-weight: 700; color: #005FB8; letter-spacing: -0.3px; margin-bottom: 1rem;">Hinweisgeberportal</div>
                <div class="auth-title">Anmeldung</div>
                <div class="auth-subtitle">Melden Sie sich an, um Ihre Hinweise zu verwalten</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('user.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">E-Mail-Adresse</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Passwort</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 1.25rem;">
                    <input type="checkbox" id="remember" name="remember" style="width: auto;">
                    <label for="remember" style="font-size: 13px; color: #374151; margin: 0; font-weight: 400;">Angemeldet bleiben</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Anmelden
                </button>
            </form>

            <hr class="divider">
            <div style="text-align: center; font-size: 13px; color: #6b7280;">
                Noch kein Konto? <a href="{{ route('register') }}" class="link">Jetzt registrieren</a>
            </div>
            <div style="text-align: center; font-size: 13px; color: #6b7280; margin-top: 8px;">
                <a href="/" class="link">Zuruck zur Startseite</a>
            </div>
        </div>
    </div>
</body>
</html>
