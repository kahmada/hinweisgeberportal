<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <header class="page-header">
        <span class="logo">Hinweisgeberportal</span>
        <nav>
            <span style="font-size: 13px; color: var(--text-muted); margin-right: 8px;">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Abmelden</button>
            </form>
        </nav>
    </header>

    <div class="container">
        <div style="margin-bottom: 1.5rem;">
            <h1 style="font-size: 20px; font-weight: 700; color: var(--text);">Hinweise</h1>
            <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Alle eingegangenen Meldungen</div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card accent">
                <div class="stat-label">Gesamt</div>
                <div class="stat-value">{{ $reports->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Eingegangen</div>
                <div class="stat-value">{{ $reports->where('status', 'eingegangen')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Prufung</div>
                <div class="stat-value">{{ $reports->where('status', 'in_pruefung')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ruckfrage</div>
                <div class="stat-value">{{ $reports->where('status', 'rueckfrage')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Abgeschlossen</div>
                <div class="stat-value">{{ $reports->where('status', 'abgeschlossen')->count() }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-body" style="padding: 1rem 1.5rem;">
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">Filter:</span>
                    <select id="statusFilter" class="form-control" style="width: auto;">
                        <option value="">Alle Status</option>
                        <option value="eingegangen">Eingegangen</option>
                        <option value="in_pruefung">In Prufung</option>
                        <option value="rueckfrage">Ruckfrage</option>
                        <option value="abgeschlossen">Abgeschlossen</option>
                    </select>
                    <select id="typeFilter" class="form-control" style="width: auto;">
                        <option value="">Alle Typen</option>
                        <option value="anonymous">Anonym</option>
                        <option value="registered">Registriert</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Hinweisgeber</th>
                        <th>Gesellschaft</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr data-status="{{ $report->status }}" data-type="{{ $report->is_anonymous ? 'anonymous' : 'registered' }}">
                        <td style="color: var(--text-muted); font-size: 13px;">#{{ $report->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ Str::limit($report->title, 45) }}</div>
                            @if($report->unread_messages_count > 0)
                                <span class="badge badge-red" style="margin-top: 3px;">{{ $report->unread_messages_count }} neu</span>
                            @endif
                        </td>
                        <td>
                            @if($report->is_anonymous)
                                <span class="badge badge-gray">Anonym</span>
                            @elseif($report->isIdentityRevealed())
                                <span style="font-size: 13px; color: var(--danger);">{{ $report->user?->name ?? '-' }}</span>
                            @else
                                <span style="font-size: 13px; color: var(--text-muted);">[Geschutzt]</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">{{ $report->company ? Str::limit($report->company, 22) : '-' }}</td>
                        <td>
                            @php
                                $badges = [
                                    'eingegangen'  => 'badge-blue',
                                    'in_pruefung'  => 'badge-yellow',
                                    'rueckfrage'   => 'badge-orange',
                                    'abgeschlossen'=> 'badge-green',
                                ];
                                $labels = [
                                    'eingegangen'  => 'Eingegangen',
                                    'in_pruefung'  => 'In Prufung',
                                    'rueckfrage'   => 'Ruckfrage',
                                    'abgeschlossen'=> 'Abgeschlossen',
                                ];
                            @endphp
                            <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}">
                                {{ $labels[$report->status] ?? $report->status }}
                            </span>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">{{ $report->created_at->format('d.m.Y') }}</td>
                        <td>
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-secondary btn-sm">Ansehen</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            Keine Hinweise vorhanden
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');

        function applyFilters() {
            const status = statusFilter.value;
            const type = typeFilter.value;
            document.querySelectorAll('tbody tr[data-status]').forEach(row => {
                const matchStatus = !status || row.dataset.status === status;
                const matchType = !type || row.dataset.type === type;
                row.style.display = matchStatus && matchType ? '' : 'none';
            });
        }

        statusFilter.addEventListener('change', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
    </script>
</body>
</html>
