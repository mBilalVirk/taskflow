<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get unread notification count
     */
    public function count()
    {
        $count = auth()->user()->notifications()
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for bell dropdown)
     */
    public function recent(Request $request)
    {
        $limit = $request->query('limit', 5);

        $notifications = auth()->user()->notifications()
            ->whereNull('read_at')
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Get all notifications
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all'); // all, unread, read

        $query = auth()->user()->notifications()->latest();

        if ($type === 'unread') {
            $query->whereNull('read_at');
        } elseif ($type === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20);

        return view('notifications.index', compact('notifications', 'type'));
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}