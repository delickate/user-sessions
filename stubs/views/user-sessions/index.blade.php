@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">User Sessions</h2>

    @if($sessions->count())
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User ID</th>
                            <th>Session ID</th>
                            <th>Login At</th>
                            <th>Logout At</th>
                            <th>Session Date</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $index => $session)
                            <tr>
                                <td>{{ $sessions->firstItem() + $index }}</td>
                                <td>{{ $session->user_id }}</td>
                                <td>{{ $session->session_id }}</td>
                                <td>{{ $session->login_at ?? '-' }}</td>
                                <td>{{ $session->logout_at ?? '-' }}</td>
                                <td>{{ $session->session_date }}</td>
                                <td>{{ $session->ip_address }}</td>
                                <td style="max-width: 250px; word-wrap: break-word;">
                                    {{ $session->user_agent }}
                                </td>
                                <td>{{ $session->created_at }}</td>
                                <td>
    <a href="{{ route('user-sessions.activities', $session->session_id) }}" 
       class="btn btn-sm btn-primary">
        View Activities
    </a>
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $sessions->links() }}
        </div>
    @else
        <div class="alert alert-info">
            No user sessions found.
        </div>
    @endif
</div>
@endsection
