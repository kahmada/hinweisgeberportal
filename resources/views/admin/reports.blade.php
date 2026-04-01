<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; }
        .header { background: white; padding: 1rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5rem; color: #333; }
        .logout-btn { background: #dc2626; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .logout-btn:hover { background: #b91c1c; }
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #666; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .stat-card .number { font-size: 2rem; font-weight: bold; color: #333; }
        .reports-table { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
        td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500; }
        .status-eingegangen { background: #dbeafe; color: #1e40af; }
        .status-in_pruefung { background: #fef3c7; color: #92400e; }
        .status-rueckfrage { background: #fce7f3; color: #9f1239; }
        .status-abgeschlossen { background: #d1fae5; color: #065f46; }
        .view-btn { background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.9rem; }
        .view-btn:hover { background: #2563eb; }
        .badge { background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>Gesamt Hinweise</h3>
                <div class="number">{{ $reports->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Eingegangen</h3>
                <div class="number">{{ $reports->where('status', 'eingegangen')->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>In Prüfung</h3>
                <div class="number">{{ $reports->where('status', 'in_pruefung')->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Abgeschlossen</h3>
                <div class="number">{{ $reports->where('status', 'abgeschlossen')->count() }}</div>
            </div>
        </div>

        <div class="reports-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Gesellschaft</th>
                        <th>Verstoß</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>#{{ $report->id }}</td>
                        <td>
                            {{ Str::limit($report->title, 40) }}
                            @if($report->unread_messages_count > 0)
                                <span class="badge">{{ $report->unread_messages_count }}</span>
                            @endif
                        </td>
                        <td>{{ $report->company ?? '-' }}</td>
                        <td>{{ $report->violation_type ?? '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ $report->status }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="view-btn">Ansehen</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                            Keine Hinweise vorhanden
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
