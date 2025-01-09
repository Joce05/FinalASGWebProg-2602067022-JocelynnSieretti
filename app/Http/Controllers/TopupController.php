<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Avatar;
use App\Models\UserAvatar;

class TopupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $avatars = Avatar::all();

        return view('topup', compact('user', 'avatars'));
    }

    public function addCoins()
    {
        $user = Auth::user();
        $user->coin += 100;
        $user->save();

        return redirect()->back()->with('success', 'Successfully added 100 coins to your wallet!');
    }

    public function purchaseAvatar(Request $request)
{
    try {
        $user = auth()->user();
        $avatar = Avatar::findOrFail($request->avatar_id);

        if ($user->coin < $avatar->price) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient coins'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Check for existing purchase - using the correct table name
            $existingPurchase = DB::table('useravatar')
                ->where('user_id', $user->id)
                ->where('avatar_id', $avatar->id)
                ->first();

            if ($existingPurchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already own this avatar'
                ]);
            }

            // Deduct coins
            $user->coin -= $avatar->price;
            $user->save();

            // Create record in useravatar table
            UserAvatar::create([
                'user_id' => $user->id,
                'avatar_id' => $avatar->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Avatar purchased successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    } catch (\Exception $e) {
        Log::error('Purchase error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your purchase.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
