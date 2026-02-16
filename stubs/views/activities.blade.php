@extends('layouts.master')

@section('title', 'Session Activities')

@section('content')
<div>
    <h4>Activities for session #{{ $session->id }} ({{ $session->user->name ?? '—' }})</h4>

    <p>Session created at: {{ optional($session->created_at)->format('Y-m-d H:i') }}</p>

    <div class="panel panel-default">
        <div class="panel-heading">Activity Logs</div>
        <div class="panel-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>When</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Old values</th>
                        <th>New values</th>
                        <th>IP</th>
                        <th>Payload</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ optional($a->created_at)->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $a->user->name ?? '—' }}</td>
                            <td>{{ $a->action }}</td>
                            <td style="max-width:250px;">
                                @if($a->old_values)
                                    <pre style="white-space:pre-wrap;word-break:break-word;max-height:200px;overflow:auto">{{ json_encode(json_decode($a->old_values), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    {{ $a->details ?? '—' }}
                                @endif
                            </td>
                            <td style="max-width:250px;">
                                @if($a->new_values)
                                    <pre style="white-space:pre-wrap;word-break:break-word;max-height:200px;overflow:auto">{{ json_encode(json_decode($a->new_values), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $a->ip_address }}</td>
                            <td>{{ $a->details ?? $a->payload }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No activity logs for this session.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:12px;">
        {{ $activities->links() }}
    </div>

    <a href="{{ route('sessions.index') }}" class="btn btn-default" style="margin-top:10px;">Back to sessions</a>
</div>
@endsection
