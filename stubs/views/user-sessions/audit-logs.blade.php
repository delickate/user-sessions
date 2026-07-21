@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        Audit Logs (Session ID: {{ $session->session_id }})
    </h2>

    <a href="{{ url('/sessions') }}" class="btn btn-secondary mb-3">
        Back to Sessions
    </a>

    @if($auditLogs->count())
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User ID</th>
                            <th>Method</th>
                            <th>Operation</th>
                            <th>Table</th>
                            <th>URL</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Created At</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Payload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auditLogs as $index => $log)
                            <tr>
                                <td>{{ $auditLogs->firstItem() + $index }}</td>
                                <td>{{ $log->user_id }}</td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $log->method }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $log->operation }}
                                    </span>
                                </td>

                                <td>{{ $log->table_name }}</td>

                                <td style="max-width:200px; word-wrap:break-word;">
                                    {{ $log->url }}
                                </td>

                                <td>{{ $log->ip_address }}</td>

                                <td style="max-width:200px; word-wrap:break-word;">
                                    {{ $log->user_agent }}
                                </td>

                                <td>{{ $log->created_at }}</td>

                                <td>
                                    @if($log->before)
                                        <details>
                                            <summary>View</summary>
                                            <pre>{{ json_encode($log->before, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($log->after)
                                        <details>
                                            <summary>View</summary>
                                            <pre>{{ json_encode($log->after, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($log->payload)
                                        <details>
                                            <summary>View</summary>
                                            <pre>{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        -
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $auditLogs->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No audit logs found for this session.
        </div>
    @endif
</div>
@endsection
