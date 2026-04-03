<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hinweis verfolgen - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 13px; font-weight: 700; color: #005FB8; letter-spacing: -0.3px; margin-bottom: 1rem;">Hinweisgeberportal</div>
                <div class="auth-title">Hinweis verfolgen</div>
                <div class="auth-subtitle">Melden Sie sich mit Ihren Zugangsdaten an</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="alert alert-info">
                Verwenden Sie die Zugangsdaten, die Sie bei der Einreichung Ihres Hinweises erhalten haben.
            </div>

            <form method="POST" action="{{ route('report.track.login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="username">Benutzername</label>
                    <input class="form-control" type="text" id="username" name="username"
                           value="{{ old('username') }}" placeholder="WB-XXXXXXXX" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Passwort</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Anmelden</button>
            </form>

            <hr class="divider">
            <div style="text-align: center; font-size: 13px; color: #6b7280;">
                <a href="/" class="link">Neuen Hinweis einreichen</a>
            </div>
        </div>
    </div>
</body>
</html>
