<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    //
    public function add(Request $request)
    {
        try {
            $user = auth()->user();
            $targetUserId = $request->input('target_user_id');
            $targetUser = User::findOrFail($targetUserId);

            // Prevent adding self to wishlist
            if ($user->id == $targetUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot add yourself to your wishlist.'
                ]);
            }

            // Check if the user is already in the wishlist
            if ($user->wishlists()->where('desired_userid', $targetUserId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already in your wishlist.'
                ]);
            }

            // Add the user to the wishlist
            Wishlist::create([
                'userid' => $user->id,
                'desired_userid' => $targetUserId
            ]);

            // Create notification for the target user
            Notification::create([
                'userid' => $targetUserId, // Send notification to target user
                'type' => 'wishlist',
                'message' => "{$user->name} added you to their wishlist.",
                'read' => false
            ]);

            // Check if mutual wishlist exists
            if ($targetUser->wishlists()->where('desired_userid', $user->id)->exists()) {
                // Create friendship if it doesn't exist
                if (!Friendship::where('userid', $user->id)->where('friendid', $targetUserId)->exists()) {
                    // Create friendship records for both users
                    Friendship::create([
                        'userid' => $user->id,
                        'friendid' => $targetUserId
                    ]);
                    Friendship::create([
                        'userid' => $targetUserId,
                        'friendid' => $user->id
                    ]);

                    // Create additional notification for matching
                    Notification::create([
                        'userid' => $targetUserId,
                        'type' => 'friendship',
                        'message' => "You and {$user->name} are now friends!",
                        'read' => false
                    ]);

                    Notification::create([
                        'userid' => $user->id,
                        'type' => 'friendship',
                        'message' => "You and {$targetUser->name} are now friends!",
                        'read' => false
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'User added to wishlist and you are now friends!'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User added to your wishlist.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }


    public function viewWishlist()
    {
        $user = auth()->user();
        $wishlistedUsers = $user->wishlists()->with('targetUser')->get();
        return view('wishlist', compact('wishlistedUsers'));
    }

    public function remove(Request $request)
    {
        $user = auth()->user();
        $targetUserId = $request->input('target_user_id');

        // Remove the wishlist entry
        $wishlist = $user->wishlists()->where('desired_userid', $targetUserId)->first();
        if ($wishlist) {
            $wishlist->delete();
        }

        // Remove both friendship records
        Friendship::where(function ($query) use ($user, $targetUserId) {
            $query->where('userid', $user->id)
                  ->where('friendid', $targetUserId);
        })->orWhere(function ($query) use ($user, $targetUserId) {
            $query->where('userid', $targetUserId)
                  ->where('friendid', $user->id);
        })->delete(); // Using delete() instead of first()->delete()

        return response()->json([
            'success' => true,
            'message' => 'User removed from wishlist and friend list.'
        ]);
    }

}
