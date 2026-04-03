<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 13px; font-weight: 700; color: #005FB8; letter-spacing: -0.3px; margin-bottom: 1rem;">Hinweisgeberportal</div>
                <div class="auth-title">Konto erstellen</div>
                <div class="auth-subtitle">Registrieren Sie sich, um Hinweise mit Ihrem Konto einzureichen</div>
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Vollständiger Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">E-Mail-Adresse</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Passwort</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                    <div class="text-sm text-muted mt-1">Mindestens 8 Zeichen</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Passwort bestätigen</label>
                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 0.5rem;">
                    Registrieren
                </button>
            </form>

            <hr class="divider">
            <div style="text-align: center; font-size: 13px; color: #6b7280;">
                Bereits registriert? <a href="{{ route('user.login') }}" class="link">Jetzt anmelden</a>
            </div>
            <div style="text-align: center; font-size: 13px; color: #6b7280; margin-top: 8px;">
                <a href="/" class="link">Zuruck zur Startseite</a>
            </div>
        </div>
    </div>
</body>
</html>
