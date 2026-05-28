<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\LessorRequestStatusNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([

            'notifications' => $request->user()
                ->notifications()
                ->latest()
                ->take(20)
                ->get()
        ]);
    }

    public function unreadCount(Request $request)
    {
        return response()->json([

            'count' => $request->user()
                ->unreadNotifications()
                ->count()
        ]);
    }

    public function markAsRead(
        string $id,
        Request $request
    ) {

        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    public function test(Request $request)
    {
        $request->user()->notify(
            new LessorRequestStatusNotification(
                'approved'
            )
        );

        return response()->json([
            'message' => 'Notification sent successfully'
        ]);
    }


    public function destroy(
        string $id,
        Request $request
    ) {

        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}
