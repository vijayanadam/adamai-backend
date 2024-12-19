<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class dashboard extends Controller
{
    public function getUserCount(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            Log::warning('User not authenticated');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Count users associated with the logged-in user
        $userCount = DB::table('users')
            ->where('client_id', $userId)
            ->count();
    
        // Count phonebots associated with the logged-in user
        $phonebotCount = DB::table('vbots')
            ->where('user_id', $userId) // Assuming 'user_id' is the column that references the user
            ->count();
    
        // Return the user count and phonebot count
        return response()->json([
            'user_count' => $userCount,
            'phonebot_count' => $phonebotCount,
        ], 200);
    }
    public function getRole(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // If no user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the user's role from the 'role' column
        $role = $user->role;

        // If the user has no role or the role is empty
        if (empty($role)) {
            return response()->json(['error' => 'User has no assigned role'], 404);
        }

        // Return the user's role
        return response()->json([
            'message' => 'User role retrieved successfully',
            'role' => $role
        ], 200);
    }
    
}
