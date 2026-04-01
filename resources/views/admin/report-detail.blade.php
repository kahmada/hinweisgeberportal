<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hinweis #{{ $report->id }} - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; }
        .header { background: white; padding: 1rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5rem; color: #333; }
        .back-btn { background: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; }
        .back-btn:hover { background: #4b5563; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h2 { margin-bottom: 1.5rem; color: #333; }
        .info-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .info-label { font-weight: 600; color: #666; width: 180px; }
        .info-value { color: #333; flex: 1; }
        .status-select { padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 4px; font-size: 14px; }
        .update-btn { background: #10b981; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        .update-btn:hover { background: #059669; }
        .messages-container { max-height: 500px; overflow-y: auto; margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 8px; }
        .message-item { margin: 15px 0; padding: 12px 15px; border-radius: 8px; max-width: 85%; }
        .message-whistleblower { background: #e3f2fd; margin-right: auto; border-left: 4px solid #2196f3; }
        .message-admin { background: #f3e5f5; margin-left: auto; border-left: 4px solid #9c27b0; }
        .message-header { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px; color: #666; }
        .message-sender { font-weight: 600; }
        .message-text { color: #333; line-height: 1.5; }
        .message-form { display: flex; gap: 10px; margin-top: 15px; }
        .message-form textarea { flex: 1; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; resize: vertical; min-height: 80px; }
        .send-btn { background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; align-self: flex-end; }
        .send-btn:hover { background: #2563eb; }
        .full-width { grid-column: 1 / -1; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hinweis #{{ $report->id }}</h1>
        <a href="{{ route('admin.reports') }}" class="back-btn">← Zurück</a>
    </div>
    
    <div class="container">
        <!-- Report Details -->
        <div class="card">
            <h2>Hinweis-Details</h2>
            
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <select id="statusSelect" class="status-select">
                        <option value="eingegangen" {{ $report->status === 'eingegangen' ? 'selected' : '' }}>Eingegangen</option>
                        <option value="in_pruefung" {{ $report->status === 'in_pruefung' ? 'selected' : '' }}>In Prüfung</option>
                        <option value="rueckfrage" {{ $report->status === 'rueckfrage' ? 'selected' : '' }}>Rückfrage</option>
                        <option value="abgeschlossen" {{ $report->status === 'abgeschlossen' ? 'selected' : '' }}>Abgeschlossen</option>
                    </select>
                    <button class="update-btn" onclick="updateStatus()">Status aktualisieren</button>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Eingereicht am:</div>
                <div class="info-value">{{ $report->created_at->format('d.m.Y H:i') }} Uhr</div>
            </div>

            <div class="info-row">
                <div class="info-label">Titel:</div>
                <div class="info-value"><strong>{{ $report->title }}</strong></div>
            </div>

            @if($report->company)
            <div class="info-row">
                <div class="info-label">Gesellschaft:</div>
                <div class="info-value">{{ $report->company }}</div>
            </div>
            @endif

            @if($report->violation_type)
            <div class="info-row">
                <div class="info-label">Verstoß:</div>
                <div class="info-value">{{ $report->violation_type }}</div>
            </div>
            @endif

            @if($report->incident_date)
            <div class="info-row">
                <div class="info-label">Datum:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d.m.Y') }}</div>
            </div>
            @endif

            @if($report->incident_location)
            <div class="info-row">
                <div class="info-label">Ort:</div>
                <div class="info-value">{{ $report->incident_location }}</div>
            </div>
            @endif

            @if($report->involved_persons)
            <div class="info-row">
                <div class="info-label">Beteiligte:</div>
                <div class="info-value">{{ $report->involved_persons }}</div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">Beschreibung:</div>
                <div class="info-value" style="white-space: pre-wrap;">{{ $report->description }}</div>
            </div>
        </div>

        <!-- Communication -->
        <div class="card">
            <h2>💬 Kommunikation</h2>
            
            <div id="messages-container" class="messages-container">
                <p style="text-align: center; color: #999;">Nachrichten werden geladen...</p>
            </div>

            <form id="messageForm" class="message-form">
                @csrf
                <textarea 
                    id="messageInput" 
                    name="message" 
                    placeholder="Nachricht an Whistleblower..." 
                    required
                ></textarea>
                <button type="submit" class="send-btn">Senden</button>
            </form>
        </div>
    </div>

    <script>
        const reportId = {{ $report->id }};
        const apiToken = '{{ auth()->user()->createToken("admin-token")->plainTextToken }}';

        // Load messages
        async function loadMessages() {
            try {
                const response = await fetch(`/api/reports/${reportId}/messages`, {
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    displayMessages(data.messages);
                    markMessagesAsRead();
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // Display messages
        function displayMessages(messages) {
            const container = document.getElementById('messages-container');
            
            if (messages.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #999;">Noch keine Nachrichten</p>';
                return;
            }

            container.innerHTML = messages.map(msg => {
                const isAdmin = msg.sender_type === 'admin';
                const date = new Date(msg.created_at);
                const formattedDate = date.toLocaleString('de-DE', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="message-item message-${msg.sender_type}">
                        <div class="message-header">
                            <span class="message-sender">${isAdmin ? 'Administrator' : 'Whistleblower'}</span>
                            <span class="message-time">${formattedDate}</span>
                        </div>
                        <div class="message-text">${escapeHtml(msg.message)}</div>
                    </div>
                `;
            }).join('');

            container.scrollTop = container.scrollHeight;
        }

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message) return;

            try {
                const response = await fetch(`/api/reports/${reportId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                if (data.success) {
                    messageInput.value = '';
                    loadMessages();
                } else {
                    alert('Fehler beim Senden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Fehler beim Senden');
            }
        });

        // Mark messages as read
        async function markMessagesAsRead() {
            try {
                await fetch(`/api/reports/${reportId}/messages/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Update status
        async function updateStatus() {
            const status = document.getElementById('statusSelect').value;
            
            try {
                const response = await fetch(`/api/reports/${reportId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Status aktualisiert!');
                } else {
                    alert('Fehler beim Aktualisieren');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Fehler beim Aktualisieren');
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load messages on page load
        loadMessages();

        // Auto-refresh every 10 seconds
        setInterval(loadMessages, 10000);
    </script>
</body>
</html>
