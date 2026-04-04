<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 1.6; }
        .container { max-width: 560px; margin: 0 auto; padding: 32px 24px; }
        .header { border-bottom: 2px solid #005FB8; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { font-size: 18px; color: #005FB8; margin: 0; }
        .row { display: flex; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .label { font-weight: 600; color: #6b7280; width: 160px; flex-shrink: 0; }
        .value { color: #1a1a1a; }
        .preview { background: #f4f6f9; border-left: 3px solid #005FB8; padding: 12px 16px; margin: 16px 0; font-style: italic; color: #374151; border-radius: 0 4px 4px 0; }
        .btn { display: inline-block; margin-top: 24px; padding: 10px 20px; background: #005FB8; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 600; }
        .footer { margin-top: 32px; font-size: 12px; color: #9ca3af; border-top: 1px solid #f0f0f0; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hinweisgeberportal — Neue Nachricht</h1>
        </div>

        <p>Ein Hinweisgeber hat eine neue Nachricht gesendet.</p>

        <div style="margin: 20px 0;">
            <div class="row">
                <div class="label">Hinweis-ID</div>
                <div class="value">#{{ $report->id }}</div>
            </div>
            <div class="row">
                <div class="label">Titel</div>
                <div class="value">{{ $report->title }}</div>
            </div>
        </div>

        <div class="preview">
            "{{ $messagePreview }}..."
        </div>

        <a href="{{ url('/admin/reports/' . $report->id) }}" class="btn">Nachricht im Portal ansehen</a>

        <div class="footer">
            Diese E-Mail wurde automatisch vom Hinweisgeberportal gesendet.<br>
            Bitte antworten Sie nicht auf diese E-Mail.
        </div>
    </div>
</body>
</html>
