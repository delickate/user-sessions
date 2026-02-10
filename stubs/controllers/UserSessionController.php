<?php

namespace App\Http\Controllers\UserSessions;

use App\Http\Controllers\Controller;
use Delickate\UserSessions\Models\UserSession;

use Illuminate\Http\Request;
use App\Models\UserSessionLogs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserSessionController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        $date = $request->query('date', Carbon::today()->toDateString());
        // ensure format yyyy-mm-dd
        try {
            $date = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            $date = Carbon::today()->toDateString();
        }

        $query = UserSessionLogs::with('user')->where(function($q) use ($date) {
            // match rows with created_at on that date OR rows where last_activity (unix) matches that date
            $q->whereDate('created_at', $date)
              ->orWhereRaw('DATE(FROM_UNIXTIME(`last_activity`)) = ?', [$date]);
        });

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $sessions = $query->orderByRaw('COALESCE(created_at, FROM_UNIXTIME(last_activity)) DESC')
                          ->paginate(20)
                          ->appends($request->query());

        // Normalize created_at to Carbon instance so views can call ->format() safely.
        $collection = $sessions->getCollection()->map(function ($item) {
            if (empty($item->created_at) && !empty($item->last_activity)) {
                $item->created_at = Carbon::createFromTimestamp((int) $item->last_activity);
            } elseif (!empty($item->created_at)) {
                $item->created_at = Carbon::parse($item->created_at);
            }
            return $item;
        });
        $sessions->setCollection($collection);

        //return view('sessions.index', compact('sessions', 'users', 'date'));
  
        return view('user-sessions.sessions.index', compact('sessions', 'users', 'date'));
    }
}
