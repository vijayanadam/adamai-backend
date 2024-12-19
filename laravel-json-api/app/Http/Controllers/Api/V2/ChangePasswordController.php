<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use LaravelJsonApi\Core\Document\Error;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    

    
    

public function updatePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'cpsd' => 'required|string',
        'npsd' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $user = Auth::user();

    if (!Hash::check($request->cpsd, $user->password)) {
        return response()->json(['error' => 'Current password does not match'], 403);
    }

    // Update the password
    $user->password = ($request->npsd);
    $user->save();

    // Revoke all tokens directly
    Token::where('user_id', $user->id)->delete();

    return response()->json(['message' => 'Password changed successfully. Please log in again.'], 200);
}


   
}

