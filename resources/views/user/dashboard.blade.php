<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Hinweise - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <header class="page-header">
        <span class="logo">{{ __('messages.common.portal_name') }}</span>
        <nav>
            <span style="font-size: 13px; color: var(--text-muted); margin-right: 8px;">{{ auth()->user()->name }}</span>
            @include('partials.lang-switcher')
            <form action="{{ route('user.logout') }}" method="POST" style="display: inline; margin-left: 8px;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">{{ __('messages.common.logout') }}</button>
            </form>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div>
                <h1 style="font-size: 20px; font-weight: 700;">{{ __('messages.user.my_reports') }}</h1>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">{{ __('messages.user.my_reports_sub') }}</div>
            </div>
            <a href="/" class="btn btn-primary btn-sm">{{ __('messages.common.new_report') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($reports->isEmpty())
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 3rem;">
                    <div style="font-size: 15px; color: var(--text-muted); margin-bottom: 1rem;">{{ __('messages.user.no_reports') }}</div>
                    <a href="/" class="btn btn-primary">{{ __('messages.user.first_report') }}</a>
                </div>
            </div>
        @else
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titel</th>
                            <th>Status</th>
                            <th>Eingereicht</th>
                            <th>Nachrichten</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr>
                            <td style="color: var(--text-muted); font-size: 13px;">#{{ $report->id }}</td>
                            <td style="font-weight: 500;">{{ Str::limit($report->title, 50) }}</td>
                            <td>
                                @php
                                    $badges = ['eingegangen'=>'badge-blue','in_pruefung'=>'badge-yellow','rueckfrage'=>'badge-orange','abgeschlossen'=>'badge-green'];
                                    $labels = ['eingegangen'=>'Eingegangen','in_pruefung'=>'In Prufung','rueckfrage'=>'Ruckfrage','abgeschlossen'=>'Abgeschlossen'];
                                @endphp
                                <span class="badge {{ $badges[$report->status] ?? 'badge-gray' }}">
                                    {{ $labels[$report->status] ?? $report->status }}
                                </span>
                            </td>
                            <td style="font-size: 13px; color: var(--text-muted);">{{ $report->created_at->format('d.m.Y') }}</td>
                            <td>
                                @if ($report->unread_messages_count > 0)
                                    <span class="badge badge-red">{{ $report->unread_messages_count }} neu</span>
                                @else
                                    <span style="font-size: 13px; color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.reports.show', $report->id) }}" class="btn btn-secondary btn-sm">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>
