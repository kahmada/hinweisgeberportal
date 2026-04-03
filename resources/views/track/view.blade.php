<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mein Hinweis - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
    <style>
        .messages-box { height: 320px; overflow-y: auto; padding: 1rem; background: #f9fafb; border: 1px solid var(--border); border-radius: var(--radius); }
    </style>
</head>
<body>
    <header class="page-header">
        <span class="logo">Hinweisgeberportal</span>
        <nav>
            <span style="font-size: 13px; color: var(--text-muted); margin-right: 8px;">{{ session('whistleblower_username') }}</span>
            <form method="POST" action="{{ route('report.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Abmelden</button>
            </form>
        </nav>
    </header>

    <div class="container" style="max-width: 860px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <h1 style="font-size: 18px; font-weight: 700;">Hinweis #{{ $report->id }}</h1>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Eingereicht am {{ $report->created_at->format('d.m.Y H:i') }} Uhr</div>
            </div>
            @php
                $badges = ['eingegangen'=>'badge-blue','in_pruefung'=>'badge-yellow','rueckfrage'=>'badge-orange','abgeschlossen'=>'badge-green'];
                $labels = ['eingegangen'=>'Eingegangen','in_pruefung'=>'In Prufung','rueckfrage'=>'Ruckfrage','abgeschlossen'=>'Abgeschlossen'];
            @endphp
            <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}" style="font-size: 13px; padding: 4px 12px;">
                {{ $labels[$report->status] ?? $report->status }}
            </span>
        </div>

        <div class="alert alert-info" style="margin-bottom: 1.25rem;">
            Sie sind anonym angemeldet. Ihre Identitat bleibt geschutzt.
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Details -->
            <div class="card">
                <div class="card-header">Hinweis-Details</div>
                <div class="card-body">
                    <div class="info-row"><div class="info-label">Titel</div><div class="info-value" style="font-weight:500;">{{ $report->title }}</div></div>
                    @if($report->company)<div class="info-row"><div class="info-label">Gesellschaft</div><div class="info-value">{{ $report->company }}</div></div>@endif
                    @if($report->violation_type)<div class="info-row"><div class="info-label">Art des Verstosses</div><div class="info-value">{{ $report->violation_type }}</div></div>@endif
                    @if($report->incident_date)<div class="info-row"><div class="info-label">Datum</div><div class="info-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d.m.Y') }}</div></div>@endif
                    @if($report->incident_location)<div class="info-row"><div class="info-label">Ort</div><div class="info-value">{{ $report->incident_location }}</div></div>@endif
                    @if($report->involved_persons)<div class="info-row"><div class="info-label">Beteiligte</div><div class="info-value">{{ $report->involved_persons }}</div></div>@endif
                    <div class="info-row"><div class="info-label">Beschreibung</div><div class="info-value" style="white-space:pre-wrap;line-height:1.6;">{{ $report->description }}</div></div>

                    @if($report->attachments->count() > 0)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Anhange</div>
                        @foreach($report->attachments as $att)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px;">
                            <span>{{ $att->original_filename }} <span class="text-muted">({{ $att->file_size_formatted }})</span></span>
                            <a href="{{ route('attachments.download', $att->id) }}" class="btn btn-secondary btn-sm">Download</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Chat -->
            <div class="card">
                <div class="card-header">Kommunikation</div>
                <div class="card-body" style="padding: 1rem;">
                    <div id="messages-container" class="messages-box">
                        <div style="text-align:center;color:var(--text-muted);font-size:13px;padding:2rem 0;">Nachrichten werden geladen...</div>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 10px;">
                        <textarea id="messageInput" class="form-control" placeholder="Ihre Nachricht..." style="min-height: 70px; flex: 1;"></textarea>
                    </div>
                    <div style="margin-top: 8px; text-align: right;">
                        <button class="btn btn-primary btn-sm" onclick="sendMessage()">Senden</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const reportId = {{ $report->id }};

        async function loadMessages() {
            try {
                const res = await fetch(`/report/${reportId}/messages`);
                const data = await res.json();
                if (data.success) { renderMessages(data.messages); markRead(); }
            } catch(e) {}
        }

        function renderMessages(messages) {
            const box = document.getElementById('messages-container');
            if (!messages.length) {
                box.innerHTML = '<div style="text-align:center;color:var(--text-muted);font-size:13px;padding:2rem 0;">Noch keine Nachrichten</div>';
                return;
            }
            box.innerHTML = messages.map(m => {
                const isUser = m.sender_type === 'whistleblower';
                const dt = new Date(m.created_at).toLocaleString('de-DE', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
                return `<div class="message-bubble ${isUser ? 'from-user' : 'from-admin'}">
                    <div class="message-meta">${isUser ? 'Sie' : 'Administrator'} &bull; ${dt}</div>
                    <div>${escHtml(m.message)}</div>
                </div>`;
            }).join('');
            box.scrollTop = box.scrollHeight;
        }

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const msg = input.value.trim();
            if (!msg) return;
            try {
                const res = await fetch(`/report/${reportId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                if (data.success) { input.value = ''; loadMessages(); }
            } catch(e) {}
        }

        async function markRead() {
            try {
                await fetch(`/report/${reportId}/messages/mark-read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
            } catch(e) {}
        }

        function escHtml(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

        loadMessages();
        setInterval(loadMessages, 10000);
    </script>
</body>
</html>
