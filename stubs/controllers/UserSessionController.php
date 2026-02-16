<?php

namespace App\Http\Controllers\UserSessions;

use App\Http\Controllers\Controller;
use Delickate\UserSessions\Models\UserSession;

use Illuminate\Http\Request;
use App\Models\UserSessionActivities;
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

        $query = UserSessionActivities::with('user')->where(function($q) use ($date) {
            // match rows with created_at on that date OR rows where hit_at (unix) matches that date
            $q->whereDate('created_at', $date)
              ->orWhereRaw('DATE(FROM_UNIXTIME(`hit_at`)) = ?', [$date]);
        });

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $sessions = $query->orderByRaw('COALESCE(created_at, FROM_UNIXTIME(hit_at)) DESC')
                          ->paginate(20)
                          ->appends($request->query());

        // Normalize created_at to Carbon instance so views can call ->format() safely.
        $collection = $sessions->getCollection()->map(function ($item) {
            if (empty($item->created_at) && !empty($item->hit_at)) {
                $item->created_at = Carbon::createFromTimestamp((int) $item->hit_at);
            } elseif (!empty($item->created_at)) {
                $item->created_at = Carbon::parse($item->created_at);
            }
            return $item;
        });
        $sessions->setCollection($collection);

        //return view('sessions.index', compact('sessions', 'users', 'date'));
  
        return view('user-sessions.index', compact('sessions', 'users', 'date'));
    }


    public function activities(Request $request, $id)
    {
        $session = UserSession::with('user')->findOrFail($id);

        $activities = UserSessionActivities::with('user')
            ->where('session_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->query());

        return view('sessions.activities', compact('session', 'activities'));
    }
}
