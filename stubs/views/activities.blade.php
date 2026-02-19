@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        Session Activities (Session ID: {{ $session->session_id }})
    </h2>

    <a href="{{ url('/sessions') }}" class="btn btn-secondary mb-3">
        Back to Sessions
    </a>

    @if($activities->count())
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User ID</th>
                            <th>Method</th>
                            <th>URL</th>
                            <th>Route Name</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Hit At</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $index => $activity)
                            <tr>
                                <td>{{ $activities->firstItem() + $index }}</td>
                                <td>{{ $activity->user_id }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $activity->method }}
                                    </span>
                                </td>
                                <td style="max-width: 250px; word-wrap: break-word;">
                                    {{ $activity->url }}
                                </td>
                                <td>{{ $activity->route_name ?? '-' }}</td>
                                <td>{{ $activity->ip_address }}</td>
                                <td style="max-width: 250px; word-wrap: break-word;">
                                    {{ $activity->user_agent }}
                                </td>
                                <td>{{ $activity->hit_at }}</td>
                                <td>{{ $activity->created_at }}</td>
                                <td>
    <a href="{{ route('user-sessions.audit-logs', $session->session_id) }}" 
       class="btn btn-sm btn-primary">
        View Detail
    </a>
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $activities->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No activities found for this session.
        </div>
    @endif
</div>
@endsection
