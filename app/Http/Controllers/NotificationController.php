<?php

namespace App\Http\Controllers;

use App\Models\NotificationModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = NotificationModel::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        $unreadCount = NotificationModel::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request, NotificationModel $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->markAsRead();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        NotificationModel::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
