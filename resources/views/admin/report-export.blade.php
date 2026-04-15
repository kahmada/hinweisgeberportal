<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hinweis #{{ $report->id }} - Export</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 12pt; line-height: 1.6; color: #1f2937; padding: 2cm; }
        h1 { font-size: 20pt; margin-bottom: 0.5cm; color: #005FB8; }
        h2 { font-size: 14pt; margin-top: 1cm; margin-bottom: 0.3cm; color: #374151; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.2cm; }
        h3 { font-size: 12pt; margin-top: 0.6cm; margin-bottom: 0.3cm; color: #4b5563; }
        .meta { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 0.4cm; margin-bottom: 0.6cm; }
        .meta-row { display: flex; padding: 0.15cm 0; border-bottom: 1px solid #f3f4f6; }
        .meta-row:last-child { border-bottom: none; }
        .meta-label { font-weight: 600; width: 5cm; color: #6b7280; }
        .meta-value { flex: 1; color: #1f2937; }
        .badge { display: inline-block; padding: 0.1cm 0.3cm; border-radius: 3px; font-size: 10pt; font-weight: 600; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-gray { background: #f3f4f6; color: #4b5563; }
        .section { margin-bottom: 0.8cm; }
        .message-box { background: #f9fafb; border-left: 3px solid #d1d5db; padding: 0.4cm; margin-bottom: 0.4cm; }
        .message-box.admin { border-left-color: #3b82f6; }
        .message-box.whistleblower { border-left-color: #10b981; }
        .message-header { font-size: 10pt; color: #6b7280; margin-bottom: 0.2cm; }
        .message-body { font-size: 11pt; color: #1f2937; }
        .log-entry { font-size: 10pt; padding: 0.2cm 0; border-bottom: 1px solid #f3f4f6; }
        .log-entry:last-child { border-bottom: none; }
        .log-time { color: #6b7280; }
        .log-action { color: #1f2937; font-weight: 500; }
        table { width: 100%; border-collapse: collapse; margin-top: 0.3cm; }
        table th { background: #f3f4f6; padding: 0.3cm; text-align: left; font-weight: 600; border-bottom: 2px solid #d1d5db; }
        table td { padding: 0.3cm; border-bottom: 1px solid #e5e7eb; }
        .footer { margin-top: 1.5cm; padding-top: 0.5cm; border-top: 1px solid #e5e7eb; font-size: 10pt; color: #6b7280; text-align: center; }
        @media print {
            body { padding: 1cm; }
            .no-print { display: none; }
            h2 { page-break-after: avoid; }
            .message-box, .log-entry { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 1cm; text-align: right;">
        <button onclick="window.print()" style="padding: 0.3cm 0.6cm; background: #005FB8; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 11pt;">Als PDF drucken</button>
        <button onclick="window.close()" style="padding: 0.3cm 0.6cm; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 11pt; margin-left: 0.3cm;">Schließen</button>
    </div>

    <h1>Hinweis #{{ $report->id }}</h1>
    <div style="font-size: 11pt; color: #6b7280; margin-bottom: 0.8cm;">Exportiert am {{ now()->format('d.m.Y H:i') }} Uhr</div>

    <div class="section">
        <h2>Übersicht</h2>
        <div class="meta">
            <div class="meta-row">
                <div class="meta-label">Titel:</div>
                <div class="meta-value">{{ $report->title }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Status:</div>
                <div class="meta-value">
                    @php
                        $badges = ['eingegangen'=>'badge-blue','in_pruefung'=>'badge-yellow','untersuchung'=>'badge-yellow','rueckfrage'=>'badge-red','abgeschlossen'=>'badge-green'];
                        $labels = ['eingegangen'=>'Eingegangen','in_pruefung'=>'In Prüfung','untersuchung'=>'Untersuchung','rueckfrage'=>'Rückfrage','abgeschlossen'=>'Abgeschlossen'];
                    @endphp
                    <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}">
                        {{ $labels[$report->status] ?? $report->status }}
                    </span>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Typ:</div>
                <div class="meta-value">
                    @if ($report->is_anonymous)
                        <span class="badge badge-gray">Anonym</span>
                    @else
                        <span class="badge badge-blue">Registriert</span>
                    @endif
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Eingereicht am:</div>
                <div class="meta-value">{{ $report->created_at->format('d.m.Y H:i') }} Uhr</div>
            </div>
            @if (!$report->is_anonymous && $report->isIdentityRevealed())
            <div class="meta-row">
                <div class="meta-label">Hinweisgeber:</div>
                <div class="meta-value">{{ $report->user->name }} ({{ $report->user->email }})</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <h2>Details zum Vorfall</h2>
        <div class="meta">
            @if ($report->company)
            <div class="meta-row">
                <div class="meta-label">Gesellschaft:</div>
                <div class="meta-value">{{ $report->company }}</div>
            </div>
            @endif
            @if ($report->violation_type)
            <div class="meta-row">
                <div class="meta-label">Art des Verstoßes:</div>
                <div class="meta-value">{{ $report->violation_type }}</div>
            </div>
            @endif
            @if ($report->incident_date)
            <div class="meta-row">
                <div class="meta-label">Datum des Vorfalls:</div>
                <div class="meta-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d.m.Y') }}</div>
            </div>
            @endif
            @if ($report->incident_location)
            <div class="meta-row">
                <div class="meta-label">Ort des Vorfalls:</div>
                <div class="meta-value">{{ $report->incident_location }}</div>
            </div>
            @endif
            @if ($report->involved_persons)
            <div class="meta-row">
                <div class="meta-label">Beteiligte Personen:</div>
                <div class="meta-value">{{ $report->involved_persons }}</div>
            </div>
            @endif
        </div>

        <h3>Beschreibung</h3>
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 0.4cm; white-space: pre-wrap;">{{ $report->description }}</div>
    </div>

    @if ($report->attachments->count() > 0)
    <div class="section">
        <h2>Anhänge ({{ $report->attachments->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Dateiname</th>
                    <th>Typ</th>
                    <th>Größe</th>
                    <th>Hochgeladen am</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report->attachments as $attachment)
                <tr>
                    <td>{{ $attachment->original_filename }}</td>
                    <td>{{ strtoupper(pathinfo($attachment->original_filename, PATHINFO_EXTENSION)) }}</td>
                    <td>{{ number_format($attachment->file_size / 1024, 1) }} KB</td>
                    <td>{{ $attachment->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if ($report->messages->count() > 0)
    <div class="section">
        <h2>Kommunikationsverlauf ({{ $report->messages->count() }} Nachrichten)</h2>
        @foreach ($report->messages->sortBy('created_at') as $message)
        <div class="message-box {{ $message->sender_type }}">
            <div class="message-header">
                <strong>{{ $message->sender_type === 'admin' ? 'Administrator' : 'Hinweisgeber' }}</strong>
                • {{ $message->created_at->format('d.m.Y H:i') }} Uhr
            </div>
            <div class="message-body">{{ $message->message }}</div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="section">
        <h2>Aktivitätsprotokoll</h2>
        @if ($activityLogs->count() > 0)
            @foreach ($activityLogs as $log)
            <div class="log-entry">
                <span class="log-time">{{ $log->created_at->format('d.m.Y H:i') }}</span>
                •
                <span class="log-action">{{ $log->action }}</span>
                @if ($log->user)
                    • von {{ $log->user->name }}
                @endif
                @if ($log->old_value || $log->new_value)
                    <span style="color: #6b7280;">
                        ({{ $log->old_value ?? '-' }} → {{ $log->new_value ?? '-' }})
                    </span>
                @endif
            </div>
            @endforeach
        @else
            <div style="color: #6b7280; font-size: 11pt;">Keine Aktivitäten protokolliert.</div>
        @endif
    </div>

    <div class="footer">
        <div>Hinweisgeberportal • Vertrauliches Dokument</div>
        <div style="margin-top: 0.2cm;">Dieser Export enthält sensible Informationen und ist ausschließlich für autorisierte Personen bestimmt.</div>
    </div>
</body>
</html>
