<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('userid', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->get()
                                   ->map(function ($notification) {
                                       // Decode the JSON data if it exists
                                       if (isset($notification->data)) {
                                           $notification->data = is_string($notification->data)
                                               ? json_decode($notification->data, true)
                                               : $notification->data;
                                       }
                                       return $notification;
                                   });

        // Mark all notifications as read when viewed
        Notification::where('userid', $user->id)
                   ->where('read', false)
                   ->update(['read' => true]);

        return view('notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('userid', Auth::id())
                                  ->findOrFail($id);
        $notification->read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }
}
