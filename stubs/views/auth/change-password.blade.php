@extends('layouts.masterTemplatePage')

@section('content')
<div class="container">
    <h3>Change Password</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Change Password
        </button>
    </form>
</div>
@endsection