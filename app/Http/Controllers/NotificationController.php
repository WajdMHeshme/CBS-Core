<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = $user->notifications()->latest();

        if ($user->hasRole('employee')) {
            $query->where('data->type', 'like', 'booking%');
        }

        $notifications = $query->paginate(20);

        return view('dashboard.notifications.index', compact('notifications'));
    }
}
