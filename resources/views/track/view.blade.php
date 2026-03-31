<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Hinweis - Hinweisgeberportal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background: #f5f5f5;
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 1.5rem 2rem; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        .header h1 { 
            font-size: 1.5rem;
        }
        .header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header .username {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .logout-btn { 
            background: rgba(255,255,255,0.2);
            color: white; 
            padding: 8px 20px; 
            border: 2px solid white;
            border-radius: 6px; 
            cursor: pointer; 
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .logout-btn:hover { 
            background: white;
            color: #667eea;
        }
        .container { 
            max-width: 900px; 
            margin: 2rem auto; 
            padding: 0 2rem;
        }
        .card { 
            background: white; 
            padding: 2rem; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .card h2 { 
            color: #333; 
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .status-eingegangen { background: #e3f2fd; color: #1565c0; }
        .status-in_pruefung { background: #fff3e0; color: #e65100; }
        .status-rueckfrage { background: #fce4ec; color: #c2185b; }
        .status-abgeschlossen { background: #e8f5e9; color: #2e7d32; }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            width: 150px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .description-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin-top: 10px;
            line-height: 1.6;
        }
        .alert-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info strong {
            color: #1565c0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Mein Hinweis</h1>
        <div class="user-info">
            <span class="username">{{ session('whistleblower_username') }}</span>
            <form method="POST" action="{{ route('report.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Abmelden</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="alert-info">
            <strong>🔒 Anonymer Zugang</strong><br>
            Sie sind anonym angemeldet. Ihre Identität bleibt geschützt.
        </div>

        <div class="card">
            <h2>Hinweis-Details</h2>
            
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $report->status }}">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Hinweis-ID:</div>
                <div class="info-value">#{{ $report->id }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Eingereicht am:</div>
                <div class="info-value">{{ $report->created_at->format('d.m.Y H:i') }} Uhr</div>
            </div>

            <div class="info-row">
                <div class="info-label">Letzte Aktualisierung:</div>
                <div class="info-value">{{ $report->updated_at->format('d.m.Y H:i') }} Uhr</div>
            </div>

            <div class="info-row">
                <div class="info-label">Titel:</div>
                <div class="info-value"><strong>{{ $report->title }}</strong></div>
            </div>

            <div class="info-row">
                <div class="info-label">Beschreibung:</div>
                <div class="info-value">
                    <div class="description-box">
                        {{ $report->description }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>💬 Kommunikation</h2>
            <p style="color: #666;">Die Kommunikationsfunktion wird in Kürze verfügbar sein.</p>
        </div>
    </div>
</body>
</html>
