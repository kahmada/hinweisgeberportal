<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hinweis #{{ $report->id }} - Admin</title>
    <link rel="stylesheet" href="/css/portal.css">
    <style>
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .messages-box { height: 360px; overflow-y: auto; padding: 1rem; background: #f9fafb; border: 1px solid var(--border); border-radius: var(--radius); }
        .log-row { display: flex; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .log-row:last-child { border-bottom: none; }
        .log-action { font-weight: 500; color: var(--text); }
        .log-meta { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="page-header">
        <span class="logo">{{ __('messages.common.portal_name') }}</span>
        <nav>
            @include('partials.lang-switcher')
            <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm" style="margin-left: 8px;">{{ __('messages.admin.back_overview') }}</a>
        </nav>
    </header>

    <div class="container">
        <div style="margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 18px; font-weight: 700;">Hinweis #{{ $report->id }}</h1>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Eingereicht am {{ $report->created_at->format('d.m.Y \u\m H:i') }} Uhr</div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                @php
                    $badges = ['eingegangen'=>'badge-blue','in_pruefung'=>'badge-yellow','rueckfrage'=>'badge-orange','abgeschlossen'=>'badge-green'];
                    $labels = ['eingegangen'=>'Eingegangen','in_pruefung'=>'In Prufung','rueckfrage'=>'Ruckfrage','abgeschlossen'=>'Abgeschlossen'];
                @endphp
                <button onclick="window.open('/admin/reports/{{ $report->id }}/export', '_blank')" class="btn btn-secondary btn-sm" title="Als PDF exportieren">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    PDF Export
                </button>
                <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}" style="font-size: 13px; padding: 4px 12px;">
                    {{ $labels[$report->status] ?? $report->status }}
                </span>
            </div>
        </div>

        <div class="two-col">
            <!-- Left: Report Details -->
            <div>
                <!-- Identity Banner -->
                @if($report->is_anonymous)
                    <div class="alert alert-info" style="margin-bottom: 1rem;">
                        Dieser Hinweis wurde anonym eingereicht. Keine personlichen Daten verfugbar.
                    </div>
                @elseif(!$report->isIdentityRevealed())
                    <div class="alert alert-warning" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>Identitat des Hinweisgebers ist geschutzt.</span>
                        <button onclick="confirmRevealIdentity()" class="btn btn-danger btn-sm">Identitat enthullen</button>
                    </div>
                @else
                    <div class="alert alert-danger" style="margin-bottom: 1rem;">
                        <strong>Identitat enthullt</strong><br>
                        Name: {{ $report->user?->name ?? '-' }}<br>
                        E-Mail: {{ $report->user?->email ?? '-' }}<br>
                        <span style="font-size: 12px; color: var(--text-muted);">
                            Enthullt am {{ $report->identity_revealed_at->format('d.m.Y H:i') }} von {{ $report->revealedBy?->name ?? '-' }}
                        </span>
                    </div>
                @endif

                <!-- Status Update -->
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header">Status andern</div>
                    <div class="card-body" style="display: flex; gap: 10px; align-items: center;">
                        <select id="statusSelect" class="form-control" style="flex: 1;">
                            <option value="eingegangen" {{ $report->status === 'eingegangen' ? 'selected' : '' }}>Eingegangen</option>
                            <option value="in_pruefung" {{ $report->status === 'in_pruefung' ? 'selected' : '' }}>In Prufung</option>
                            <option value="rueckfrage" {{ $report->status === 'rueckfrage' ? 'selected' : '' }}>Ruckfrage</option>
                            <option value="abgeschlossen" {{ $report->status === 'abgeschlossen' ? 'selected' : '' }}>Abgeschlossen</option>
                        </select>
                        <button class="btn btn-primary btn-sm" onclick="updateStatus()">Speichern</button>
                    </div>
                </div>

                <!-- Report Info -->
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header">Hinweis-Details</div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Titel</div>
                            <div class="info-value" style="font-weight: 500;">{{ $report->title }}</div>
                        </div>
                        @if($report->company)
                        <div class="info-row">
                            <div class="info-label">Gesellschaft</div>
                            <div class="info-value">{{ $report->company }}</div>
                        </div>
                        @endif
                        @if($report->violation_type)
                        <div class="info-row">
                            <div class="info-label">Art des Verstosses</div>
                            <div class="info-value">{{ $report->violation_type }}</div>
                        </div>
                        @endif
                        @if($report->incident_date)
                        <div class="info-row">
                            <div class="info-label">Datum</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d.m.Y') }}</div>
                        </div>
                        @endif
                        @if($report->incident_location)
                        <div class="info-row">
                            <div class="info-label">Ort</div>
                            <div class="info-value">{{ $report->incident_location }}</div>
                        </div>
                        @endif
                        @if($report->involved_persons)
                        <div class="info-row">
                            <div class="info-label">Beteiligte</div>
                            <div class="info-value">{{ $report->involved_persons }}</div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Beschreibung</div>
                            <div class="info-value" style="white-space: pre-wrap; line-height: 1.6;">{{ $report->description }}</div>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                @if($report->attachments->count() > 0)
                <div class="card">
                    <div class="card-header">Anhange ({{ $report->attachments->count() }})</div>
                    <div class="card-body" style="padding: 0.75rem 1.5rem;">
                        @foreach($report->attachments as $att)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                            <span style="font-size: 13px;">{{ $att->original_filename }} <span class="text-muted">({{ $att->file_size_formatted }})</span></span>
                            <a href="{{ route('attachments.download', $att->id) }}" class="btn btn-secondary btn-sm">Download</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Communication -->
            <div>
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-header">Kommunikation</div>
                    <div class="card-body" style="padding: 1rem;">
                        <div id="messages-container" class="messages-box">
                            <div style="text-align: center; color: var(--text-muted); font-size: 13px; padding: 2rem 0;">Nachrichten werden geladen...</div>
                        </div>
                        <div id="chatClosedNotice" style="display:none; background:#fef2f2; border:1px solid #fca5a5; border-radius:4px; padding:10px 14px; margin-top:10px; color:#991b1b; font-size:13px;">
                            &#9888; Dieser Hinweis ist abgeschlossen. Ändern Sie den Status, um wieder Nachrichten senden zu können.
                        </div>
                        <div id="messageSendError" style="display:none; background:#fef2f2; border:1px solid #fca5a5; border-radius:4px; padding:8px 12px; margin-top:8px; color:#991b1b; font-size:12px;"></div>
                        <div style="display: flex; gap: 8px; margin-top: 10px;">
                            <textarea id="messageInput" class="form-control" placeholder="Nachricht an Hinweisgeber..." style="min-height: 70px; flex: 1;"></textarea>
                        </div>
                        <div style="margin-top: 8px; text-align: right;">
                            <button id="sendMessageBtn" class="btn btn-primary btn-sm" onclick="sendMessage()">Senden</button>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="card">
                    <div class="card-header">Aktivitatsprotokoll</div>
                    <div class="card-body" style="padding: 0.75rem 1.5rem; max-height: 300px; overflow-y: auto;">
                        @forelse($activityLogs as $log)
                        <div class="log-row">
                            <div style="flex: 1;">
                                <div class="log-action">
                                    {{ $log->action_label }}
                                    @if($log->old_value && $log->new_value)
                                        <span class="badge badge-gray" style="margin-left: 4px;">{{ $log->old_value }}</span>
                                        <span style="color: var(--text-muted); margin: 0 2px;">→</span>
                                        <span class="badge badge-blue">{{ $log->new_value }}</span>
                                    @endif
                                </div>
                                <div class="log-meta">
                                    {{ $log->user?->name ?? 'System' }} &bull; {{ $log->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; color: var(--text-muted); font-size: 13px; padding: 1rem 0;">Keine Aktivitaten</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const reportId = {{ $report->id }};
        const reportStatus = '{{ $report->status }}';

        function initChatUI() {
            if (reportStatus === 'abgeschlossen') {
                const notice = document.getElementById('chatClosedNotice');
                const input = document.getElementById('messageInput');
                const btn = document.getElementById('sendMessageBtn');
                if (notice) notice.style.display = 'block';
                if (input) { input.disabled = true; input.style.opacity = '0.5'; input.placeholder = 'Hinweis abgeschlossen – Status ändern, um Nachrichten zu senden'; }
                if (btn) { btn.disabled = true; btn.style.opacity = '0.5'; }
            }
        }

        async function loadMessages() {
            try {
                const res = await fetch(`/api/reports/${reportId}/messages`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                });
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
                const isAdmin = m.sender_type === 'admin';
                const dt = new Date(m.created_at).toLocaleString('de-DE', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
                return `<div class="message-bubble ${isAdmin ? 'from-admin' : 'from-user'}">
                    <div class="message-meta">${isAdmin ? 'Administrator' : 'Hinweisgeber'} &bull; ${dt}</div>
                    <div>${escHtml(m.message)}</div>
                </div>`;
            }).join('');
            box.scrollTop = box.scrollHeight;
        }

        async function sendMessage() {
            if (reportStatus === 'abgeschlossen') return;
            const input = document.getElementById('messageInput');
            const errEl = document.getElementById('messageSendError');
            const msg = input.value.trim();
            if (!msg) return;
            if (errEl) errEl.style.display = 'none';
            try {
                const res = await fetch(`/api/reports/${reportId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                if (data.success) { input.value = ''; loadMessages(); }
                else if (errEl) { errEl.textContent = data.message || 'Fehler beim Senden.'; errEl.style.display = 'block'; }
            } catch(e) {
                if (errEl) { errEl.textContent = 'Verbindungsfehler. Bitte versuchen Sie es erneut.'; errEl.style.display = 'block'; }
            }
        }

        async function markRead() {
            try {
                await fetch(`/api/reports/${reportId}/messages/mark-read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    credentials: 'same-origin'
                });
            } catch(e) {}
        }

        async function updateStatus() {
            const status = document.getElementById('statusSelect').value;
            try {
                const res = await fetch(`/api/reports/${reportId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ status })
                });
                const data = await res.json();
                if (data.success) location.reload();
            } catch(e) {}
        }

        async function confirmRevealIdentity() {
            if (!confirm('Identitat enthullen?\n\nDiese Aktion wird protokolliert und kann nicht ruckgangig gemacht werden.')) return;
            try {
                const res = await fetch(`/api/reports/${reportId}/reveal-identity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                const data = await res.json();
                if (data.success) location.reload();
                else alert(data.message || 'Fehler');
            } catch(e) {}
        }

        function escHtml(t) {
            const d = document.createElement('div');
            d.textContent = t;
            return d.innerHTML;
        }

        initChatUI();
        loadMessages();
        setInterval(loadMessages, 10000);
    </script>
</body>
</html>
