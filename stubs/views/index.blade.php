@extends('layouts.app')

@section('title', 'Sessions')

@section('content')
<div >
    <h4>Sessions</h4>

    <form method="GET" action="{{ route('sessions.index') }}" class="form-inline" style="margin-bottom:15px;">
        <div class="form-group">
            <label for="user_id" style="margin-right:8px;">User</label>
            <select name="user_id" id="user_id" class="form-control" style="margin-right:15px;">
                <option value="">All users</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-right:15px;">
            <label for="date" style="margin-right:8px;">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
        </div>

        <button class="btn btn-primary" type="submit">Filter</button>
        <a href="{{ route('sessions.index') }}" class="btn btn-default" style="margin-left:8px;">Reset</a>
    </form>

    <div class="panel panel-default">
        <div class="panel-heading">Sessions for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</div>
        <div class="panel-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Created At</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $s->user->name ?? '—' }}</td>
                            <td>{{ $s->ip_address }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($s->user_agent, 100) }}</td>
                            <td>
                                <a href="{{ route('sessions.activities', $s->id) }}" class="btn btn-sm btn-default">Activities</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No sessions found for this date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:12px;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection