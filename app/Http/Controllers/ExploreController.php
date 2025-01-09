<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class ExploreController extends Controller
{
    //

    public function search(Request $request)
{
    $search = $request->input('hobby');
    $gender = $request->input('gender');

    // Start building the query
    $query = User::query();

    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('hobby', 'LIKE', '%' . $search . '%');
        });
    }

    // Apply the gender filter if selected
    if ($gender) {
        $query->where('gender', $gender);
    }

    // Execute the query and get the results
    $users = $query->get();

    // Pass the results to the view
    return view('explore', compact('users', 'search', 'gender'));
}

}
