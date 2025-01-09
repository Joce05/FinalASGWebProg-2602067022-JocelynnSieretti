<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Str;
use App\Models\Avatar;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $currentUserId = $user->id;

    // Get purchased avatars
    $purchasedAvatars = Avatar::whereHas('users', function($query) use ($user) {
        $query->where('useravatar.user_id', $user->id);  // Specify the table name
    })->get();

        // Get friends
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

        return view('profile', compact('user', 'friendUsers', 'purchasedAvatars'));
    }

    // Handle the update of profile data
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hobby' => 'array',
            'hobby.*' => 'in:Cooking,Painting,Hiking,Traveling,Gaming',
            'instagram' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phonenumber' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Basic info update
        $user->name = $request->input('name');
        $user->hobby = json_encode($request->input('hobby', []));
        $user->instagram = $request->input('instagram', '');
        $user->address = $request->input('address', '');
        $user->email = $request->input('email', '');
        $user->phonenumber = $request->input('phonenumber', '');

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                // Store the file
                $path = $request->file('image')->storeAs('images', $filename, 'public');

                // Save the path to database
                $user->image = $path;

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error uploading image: ' . $e->getMessage());
            }
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

}
