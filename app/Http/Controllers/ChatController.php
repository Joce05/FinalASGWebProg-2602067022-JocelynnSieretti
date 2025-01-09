<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->User();
        $currentUserId = $user->id;

        $friends = DB::table('friendships')
            ->where(function ($query) use ($currentUserId) {
                $query->where('userid', $currentUserId)
                      ->orWhere('friendid', $currentUserId);
            })
            ->get();

        $friendIds = $friends->pluck('friendid')
            ->filter(function($id) use ($currentUserId) {
                return $id != $currentUserId;
            })
            ->merge(
                $friends->pluck('userid')->filter(function($id) use ($currentUserId) {
                    return $id != $currentUserId;
                })
            )
            ->unique()
            ->values()
            ->toArray();

        $friendUsers = User::whereIn('id', $friendIds)->get();

        return view('chat', compact('user', 'friendUsers'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiverid' => 'required|exists:users,id',
        ]);

        // Add this line to get the authenticated user
        $user = Auth::user();

        $message = new Message();
        $message->message = $request->message;
        $message->userid = Auth::id();
        $message->receiverid = $request->receiverid;
        $message->save();

        Notification::create([
            'userid' => $request->receiverid,
            'type' => 'message',
            'message' => "{$user->name} sent you a message: " . Str::limit($request->message, 50),
            'data' => json_encode(['sender_id' => $user->id])
        ]);

        return redirect()->route('chat.start', ['friendid' => $request->receiverid])
                        ->with('success', 'Message sent successfully');
    }

    public function startChat($friendid)
    {
    $user = Auth::user();
    $friend = User::findOrFail($friendid);

    $messages = Message::where(function ($query) use ($user, $friend) {
        $query->where('userid', $user->id)
              ->where('receiverid', $friend->id);
    })->orWhere(function ($query) use ($user, $friend) {
        $query->where('userid', $friend->id)
              ->where('receiverid', $user->id);
    })->orderBy('created_at', 'asc')->get();

    return view('privatechat', compact('messages', 'friend'));
    }
}
