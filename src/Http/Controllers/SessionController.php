<?php

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = UserSession::with('user')
            ->when($request->user_id, fn ($q) =>
                $q->where('user_id', $request->user_id)
            )
            ->when($request->date, fn ($q) =>
                $q->whereDate('session_date', $request->date)
            )
            ->latest()
            ->paginate(20);

        return view('user-sessions::sessions.index', compact('sessions'));
    }
}
