<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            @if($report->company)
            <div class="info-row">
                <div class="info-label">Gesellschaft:</div>
                <div class="info-value">{{ $report->company }}</div>
            </div>
            @endif

            @if($report->violation_type)
            <div class="info-row">
                <div class="info-label">Art des Verstoßes:</div>
                <div class="info-value">{{ $report->violation_type }}</div>
            </div>
            @endif

            @if($report->incident_date)
            <div class="info-row">
                <div class="info-label">Datum des Vorfalls:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($report->incident_date)->format('d.m.Y') }}</div>
            </div>
            @endif

            @if($report->incident_location)
            <div class="info-row">
                <div class="info-label">Ort des Vorfalls:</div>
                <div class="info-value">{{ $report->incident_location }}</div>
            </div>
            @endif

            @if($report->involved_persons)
            <div class="info-row">
                <div class="info-label">Beteiligte Personen:</div>
                <div class="info-value">{{ $report->involved_persons }}</div>
            </div>
            @endif

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
            
            <div id="messages-container" style="max-height: 400px; overflow-y: auto; margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                <p style="text-align: center; color: #999;">Nachrichten werden geladen...</p>
            </div>

            <form id="messageForm" style="display: flex; gap: 10px;">
                @csrf
                <textarea 
                    id="messageInput" 
                    name="message" 
                    placeholder="Ihre Nachricht eingeben..." 
                    style="flex: 1; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; resize: vertical; min-height: 60px;"
                    required
                ></textarea>
                <button 
                    type="submit" 
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; align-self: flex-end;"
                >
                    Senden
                </button>
            </form>
        </div>
    </div>

    <style>
        .message-item {
            margin: 15px 0;
            padding: 12px 15px;
            border-radius: 8px;
            max-width: 80%;
        }
        .message-whistleblower {
            background: #e3f2fd;
            margin-left: auto;
            border-left: 4px solid #2196f3;
        }
        .message-admin {
            background: #f3e5f5;
            margin-right: auto;
            border-left: 4px solid #9c27b0;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
            color: #666;
        }
        .message-sender {
            font-weight: 600;
        }
        .message-time {
            font-style: italic;
        }
        .message-text {
            color: #333;
            line-height: 1.5;
        }
    </style>

    <script>
        const reportId = {{ $report->id }};
        let messageCheckInterval;

        // Load messages
        async function loadMessages() {
            try {
                const response = await fetch(`/report/${reportId}/messages`);
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
                container.innerHTML = '<p style="text-align: center; color: #999;">Noch keine Nachrichten. Starten Sie die Konversation!</p>';
                return;
            }

            container.innerHTML = messages.map(msg => {
                const isWhistleblower = msg.sender_type === 'whistleblower';
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
                            <span class="message-sender">${isWhistleblower ? 'Sie' : 'Administrator'}</span>
                            <span class="message-time">${formattedDate}</span>
                        </div>
                        <div class="message-text">${escapeHtml(msg.message)}</div>
                    </div>
                `;
            }).join('');

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message) return;

            try {
                const response = await fetch(`/report/${reportId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                if (data.success) {
                    messageInput.value = '';
                    loadMessages();
                } else {
                    alert('Fehler beim Senden der Nachricht');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Fehler beim Senden der Nachricht');
            }
        });

        // Mark messages as read
        async function markMessagesAsRead() {
            try {
                await fetch(`/report/${reportId}/messages/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            } catch (error) {
                console.error('Error marking messages as read:', error);
            }
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load messages on page load
        loadMessages();

        // Auto-refresh messages every 10 seconds
        messageCheckInterval = setInterval(loadMessages, 10000);

        // Clear interval on page unload
        window.addEventListener('beforeunload', () => {
            clearInterval(messageCheckInterval);
        });
    </script>
</body>
</html>
