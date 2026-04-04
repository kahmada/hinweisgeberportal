<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.admin.dashboard') }} - {{ __('messages.common.portal_name') }}</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <header class="page-header">
        <span class="logo">{{ __('messages.common.portal_name') }}</span>
        <nav>
            <span style="font-size: 13px; color: var(--text-muted); margin-right: 8px;">{{ auth()->user()->name }}</span>
            @include('partials.lang-switcher')
            <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 8px;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">{{ __('messages.common.logout') }}</button>
            </form>
        </nav>
    </header>

    <div class="container">
        <div style="margin-bottom: 1.5rem;">
            <h1 style="font-size: 20px; font-weight: 700; color: var(--text);">{{ __('messages.admin.dashboard') }}</h1>
            <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">{{ __('messages.admin.dashboard_sub') }}</div>
        </div>

        <div class="stats-grid">
            <div class="stat-card accent">
                <div class="stat-label">{{ __('messages.admin.total') }}</div>
                <div class="stat-value">{{ $reports->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">{{ __('messages.common.status.eingegangen') }}</div>
                <div class="stat-value">{{ $reports->where('status', 'eingegangen')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">{{ __('messages.common.status.in_pruefung') }}</div>
                <div class="stat-value">{{ $reports->where('status', 'in_pruefung')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">{{ __('messages.common.status.rueckfrage') }}</div>
                <div class="stat-value">{{ $reports->where('status', 'rueckfrage')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">{{ __('messages.common.status.abgeschlossen') }}</div>
                <div class="stat-value">{{ $reports->where('status', 'abgeschlossen')->count() }}</div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-body" style="padding: 1rem 1.5rem;">
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">{{ __('messages.admin.filter') }}</span>
                    <select id="statusFilter" class="form-control" style="width: auto;">
                        <option value="">{{ __('messages.admin.all_status') }}</option>
                        <option value="eingegangen">{{ __('messages.common.status.eingegangen') }}</option>
                        <option value="in_pruefung">{{ __('messages.common.status.in_pruefung') }}</option>
                        <option value="rueckfrage">{{ __('messages.common.status.rueckfrage') }}</option>
                        <option value="abgeschlossen">{{ __('messages.common.status.abgeschlossen') }}</option>
                    </select>
                    <select id="typeFilter" class="form-control" style="width: auto;">
                        <option value="">{{ __('messages.admin.all_types') }}</option>
                        <option value="anonymous">{{ __('messages.admin.only_anonymous') }}</option>
                        <option value="registered">{{ __('messages.admin.only_registered') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.admin.col_id') }}</th>
                        <th>{{ __('messages.admin.col_title') }}</th>
                        <th>{{ __('messages.admin.col_submitter') }}</th>
                        <th>{{ __('messages.admin.col_company') }}</th>
                        <th>{{ __('messages.admin.col_status') }}</th>
                        <th>{{ __('messages.admin.col_date') }}</th>
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
                                <span class="badge badge-red" style="margin-top: 3px;">{{ $report->unread_messages_count }} {{ __('messages.admin.new_badge') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($report->is_anonymous)
                                <span class="badge badge-gray">{{ __('messages.admin.only_anonymous') }}</span>
                            @elseif($report->isIdentityRevealed())
                                <span style="font-size: 13px; color: var(--danger);">{{ $report->user?->name ?? '-' }}</span>
                            @else
                                <span style="font-size: 13px; color: var(--text-muted);">{{ __('messages.admin.protected') }}</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">{{ $report->company ? Str::limit($report->company, 22) : '-' }}</td>
                        <td>
                            @php $badges = ['eingegangen'=>'badge-blue','in_pruefung'=>'badge-yellow','rueckfrage'=>'badge-orange','abgeschlossen'=>'badge-green']; @endphp
                            <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}">
                                {{ __('messages.common.status.' . $report->status) }}
                            </span>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">{{ $report->created_at->format('d.m.Y') }}</td>
                        <td><a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-secondary btn-sm">{{ __('messages.admin.view') }}</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">{{ __('messages.admin.no_reports') }}</td>
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
