<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hinweis verfolgen - Hinweisgeberportal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container { 
            max-width: 450px; 
            width: 100%;
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 { 
            color: #333; 
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 500; 
            color: #555; 
        }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 14px 24px; 
            border: none; 
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: 600;
            cursor: pointer; 
            width: 100%;
            transition: transform 0.2s;
        }
        button:hover { 
            transform: translateY(-2px);
        }
        .error { 
            padding: 12px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            background: #fee; 
            color: #c33; 
            border: 1px solid #fcc;
            font-size: 14px;
        }
        .info { 
            padding: 12px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            background: #e3f2fd; 
            color: #1565c0; 
            border: 1px solid #90caf9;
            font-size: 14px;
        }
        .security-note {
            margin-top: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 6px;
            font-size: 13px;
            color: #666;
        }
        .security-note strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Hinweis verfolgen</h1>
            <p>Melden Sie sich mit Ihren Zugangsdaten an</p>
        </div>
        
        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="info">
            ℹ️ Verwenden Sie die Zugangsdaten, die Sie bei der Einreichung Ihres Hinweises erhalten haben.
        </div>

        <form method="POST" action="{{ route('report.track.login') }}">
            @csrf
            
            <div class="form-group">
                <label for="username">Benutzername</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}" 
                    placeholder="WB-XXXXXXXX"
                    required 
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Passwort</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Ihr Passwort"
                    required
                >
            </div>

            <button type="submit">Anmelden</button>
        </form>

        <div class="security-note">
            <strong>🔐 Sicherheitshinweis:</strong><br>
            Ihre Identität bleibt vollständig anonym. Wir speichern keine persönlichen Daten, die Rückschlüsse auf Ihre Person zulassen.
        </div>
    </div>
</body>
</html>
