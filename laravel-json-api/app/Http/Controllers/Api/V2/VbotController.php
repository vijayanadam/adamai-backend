<?php
namespace App\Http\Controllers\Api\V2;
use App\Http\Controllers\Controller;

use App\Models\Vbot;
use App\Models\CallLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class VbotController extends Controller
{
    public function index()
    {
        // Return a list of Vbots
        $vbots = Vbot::all();
        return response()->json($vbots);
    }

    public function addVbot(Request $request)
    {
        // Validate the request, no fields are required
        $validatedData = $request->validate([
            'bezeichnung' => 'nullable|string|max:255',
           
            'product_name' => 'nullable|string|max:255',
            'sip_benutzername' => 'nullable|string|max:255',
            'sip_passwd' => 'nullable|string|max:255',
            'sip_registername' => 'nullable|string|max:255',
            'sip_phonenumber' => 'nullable|string|max:255',
            'sip_server' => 'nullable|string|max:255',
            'sip_port' => 'nullable|string|max:255',
            'e_mail_server' => 'nullable|string|max:255',
            'e_mail_user' => 'nullable|string|max:255',
            'e_mail_passwd' => 'nullable|string|max:255',
            'e_mail_server_port' => 'nullable|string|max:255',
            'e_mail_server_from' => 'nullable|string|max:255',
            'ansagetext' => 'nullable|string',
            'prompt' => 'nullable|string',
            'chatmodel' => 'nullable|string|max:255',
            'chatmodel_api' => 'nullable|string|max:255',
            'sttmodel' => 'nullable|string|max:255',
            'sttmodel_api' => 'nullable|string|max:255',
            'ttsmodel' => 'nullable|string|max:255',
            'ttsmodel_api' => 'nullable|string|max:255',
            'attendedTransfer' => 'nullable|boolean',
            'mail_certifikate_validation' => 'nullable|boolean',
            'prompturl' => 'nullable|string|max:255',
            'transmit_type' => 'nullable|string|max:255',
            'stun_server' => 'nullable|string|max:255',
        ]);

        // Generate a unique license key
         $validatedData['license_Key'] = Vbot::generateUniqueLicenseKey();
         $validatedData['user_id']  = auth()->id();
         $validatedData['status']  = 'New';

        // Create a new Vbot instance and save it
        $vbot = Vbot::create($validatedData);

        return response()->json(['message' => 'Vbot created successfully!', 'data' => $vbot], 201);
    }
    public function getVbot(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            Log::warning('User not authenticated');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Retrieve users associated with the logged-in user, ordered by created_at descending
        $users = DB::table('vbots')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc') // Order by created_at in descending order
            ->get();
    
        // Check if users exist
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found for this client.'], 404);
        }
    
        // Return the list of users
        return response()->json($users, 200);
    }
    
    public function getVbotById($id)
    {
        
    
            // Fetch user data using the DB query builder
            $vbot = DB::table('vbots')->where('id', $id)->first();
    
            if (!$vbot) {
                Log::warning('vbot not found');
                return response()->json(['error' => 'vbot not found'], 404);
            }
    
            Log::info('Returning user data for ID: ' . $id);
            return response()->json($vbot, 200); // Return user data
    }

    public function updateVbot(Request $request, $id)
{
    // Validate the request data
    $request->validate([
       'bezeichnung' => 'nullable|string|max:255',
            
            'product_name' => 'nullable|string|max:255',
            'sip_benutzername' => 'nullable|string|max:255',
            'sip_passwd' => 'nullable|string|max:255',
            'sip_registername' => 'nullable|string|max:255',
            'sip_phonenumber' => 'nullable|string|max:255',
            'sip_server' => 'nullable|string|max:255',
            'sip_port' => 'nullable|string|max:255',
            'e_mail_server' => 'nullable|string|max:255',
            'e_mail_user' => 'nullable|string|max:255',
            'e_mail_passwd' => 'nullable|string|max:255',
            'e_mail_server_port' => 'nullable|string|max:255',
            'e_mail_server_from' => 'nullable|string|max:255',
            'ansagetext' => 'nullable|string',
            'prompt' => 'nullable|string',
            'chatmodel' => 'nullable|string|max:255',
            'chatmodel_api' => 'nullable|string|max:255',
            'sttmodel' => 'nullable|string|max:255',
            'sttmodel_api' => 'nullable|string|max:255',
            'ttsmodel' => 'nullable|string|max:255',
            'ttsmodel_api' => 'nullable|string|max:255',
            'attendedtransfer' => 'nullable|boolean',
            'mail_certifikate_validation' => 'nullable|boolean',
            'prompturl' => 'nullable|string|max:255',
            'transmit_type' => 'nullable|string|max:255',
            'stun_server' => 'nullable|string|max:255',
    ]);

    // Find the user by ID
    $vbot = Vbot::findOrFail($id);

    // Update the user's profile with the validated data
    $vbot->bezeichnung = $request->input('bezeichnung');
   
    $vbot->product_name = $request->input('product_name');
    $vbot->sip_benutzername = $request->input('sip_benutzername');
    $vbot->sip_passwd = $request->input('sip_passwd');
    $vbot->sip_registername = $request->input('sip_registername');
    $vbot->sip_phonenumber = $request->input('sip_phonenumber');
    $vbot->sip_server = $request->input('sip_server');
    $vbot->sip_port = $request->input('sip_port');
    $vbot->e_mail_server = $request->input('e_mail_server');
    $vbot->e_mail_user = $request->input('e_mail_user');
    $vbot->e_mail_passwd = $request->input('e_mail_passwd');
    $vbot->e_mail_server_from = $request->input('e_mail_server_from');
    $vbot->mail_certifikate_validation = $request->input('mail_certifikate_validation');
    $vbot->ansagetext = $request->input('ansagetext');
    $vbot->prompt = $request->input('prompt');
    $vbot->chatmodel = $request->input('chatmodel');
    $vbot->chatmodel_api = $request->input('chatmodel_api');
    $vbot->sttmodel = $request->input('sttmodel');
    $vbot->sttmodel_api = $request->input('sttmodel_api');
    $vbot->ttsmodel = $request->input('ttsmodel');
    $vbot->ttsmodel_api = $request->input('ttsmodel_api');
    $vbot->attendedtransfer = $request->input('attendedtransfer');
    $vbot->prompturl = $request->input('prompturl');
    $vbot->transmit_type = $request->input('transmit_type');
    $vbot->stun_server = $request->input('stun_server');
    
    // Save the changes
    $vbot->save();

    // Return a simple JSON response with success message and updated user data
    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $vbot
    ], 200);
}

    public function destroy($id)
    {
        // Find the Vbot
        $vbot = Vbot::findOrFail($id);
        CallLog::where('phonebot_id', $id)->delete();
        // Delete the Vbot instance
        $vbot->delete();

        return response()->json(['message' => 'Vbot deleted successfully!']);
    }

    public function getPhonebot(Request $request)
    {
        $vbots = Vbot::orderBy('id', 'desc')->get();

        // If no records found, return an error
        if ($vbots->isEmpty()) {
            return response()->json([
                'error' => 'No vbots found.'
            ], 404);
        }
    
        // Add 'status' and format 'created_at'
        $vbots->transform(function ($vbot) {
            // Treat empty string as null for open_ai_key
            $openAiKey = $vbot->apen_ai_key ?: null;
            $vbot->pstatus = is_null($openAiKey) ? 'inactive' : 'active';
    
            // Format the 'created_at' field
            // Parse the ISO 8601 date string and format it
          
    
            return $vbot;
        });
    
        // Return the results with the added status field and formatted date
        return response()->json([
            'message' => 'Vbots fetched successfully.',
            'data' => $vbots
        ], 200);
    }
    
    public function updatePhonebot(Request $request, $id)
{
    // Validate the request data
    $request->validate([
       'bezeichnung' => 'nullable|string|max:255',
            
            'product_name' => 'nullable|string|max:255',
            'sip_benutzername' => 'nullable|string|max:255',
            'sip_passwd' => 'nullable|string|max:255',
            'sip_registername' => 'nullable|string|max:255',
            'sip_phonenumber' => 'nullable|string|max:255',
            'sip_server' => 'nullable|string|max:255',
            'sip_port' => 'nullable|string|max:255',
            'e_mail_server' => 'nullable|string|max:255',
            'e_mail_user' => 'nullable|string|max:255',
            'e_mail_passwd' => 'nullable|string|max:255',
            'e_mail_server_port' => 'nullable|string|max:255',
            'e_mail_server_from' => 'nullable|string|max:255',
            'ansagetext' => 'nullable|string',
            'prompt' => 'nullable|string',
            'chatmodel' => 'nullable|string|max:255',
            'chatmodel_api' => 'nullable|string|max:255',
            'sttmodel' => 'nullable|string|max:255',
            'sttmodel_api' => 'nullable|string|max:255',
            'ttsmodel' => 'nullable|string|max:255',
            'ttsmodel_api' => 'nullable|string|max:255',
            'attendedtransfer' => 'nullable|boolean',
            'mail_certifikate_validation' => 'nullable|boolean',
            'apen_ai_key' => 'nullable|string|max:255',
            'prompturl' => 'nullable|string|max:255',
            'transmit_type' => 'nullable|string|max:255',
            'stun_server' => 'nullable|string|max:255',
    ]);

    // Find the user by ID
    $vbot = Vbot::findOrFail($id);

    // Update the user's profile with the validated data
    $vbot->bezeichnung = $request->input('bezeichnung');
   
    $vbot->product_name = $request->input('product_name');
    $vbot->sip_benutzername = $request->input('sip_benutzername');
    $vbot->sip_passwd = $request->input('sip_passwd');
    $vbot->sip_registername = $request->input('sip_registername');
    $vbot->sip_phonenumber = $request->input('sip_phonenumber');
    $vbot->sip_server = $request->input('sip_server');
    $vbot->sip_port = $request->input('sip_port');
    $vbot->e_mail_server = $request->input('e_mail_server');
    $vbot->e_mail_user = $request->input('e_mail_user');
    $vbot->e_mail_passwd = $request->input('e_mail_passwd');
    $vbot->e_mail_server_from = $request->input('e_mail_server_from');
    $vbot->mail_certifikate_validation = $request->input('mail_certifikate_validation');
    $vbot->ansagetext = $request->input('ansagetext');
    $vbot->prompt = $request->input('prompt');
    $vbot->chatmodel = $request->input('chatmodel');
    $vbot->chatmodel_api = $request->input('chatmodel_api');
    $vbot->sttmodel = $request->input('sttmodel');
    $vbot->sttmodel_api = $request->input('sttmodel_api');
    $vbot->ttsmodel = $request->input('ttsmodel');
    $vbot->ttsmodel_api = $request->input('ttsmodel_api');
    $vbot->attendedtransfer = $request->input('attendedtransfer');
    $vbot->apen_ai_key = $request->input('apen_ai_key');
    $vbot->prompturl = $request->input('prompturl');
    $vbot->transmit_type = $request->input('transmit_type');
    $vbot->stun_server = $request->input('stun_server');
    
    // Save the changes
    $vbot->save();

    // Return a simple JSON response with success message and updated user data
    return response()->json([
        'message' => 'Phonebot updated successfully.',
        'user' => $vbot
    ], 200);
}
public function updateProfile(Request $request)
{
    // Validate the request data
    $request->validate([
       
        'name' => 'required|string|max:255',
        
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        
    ]);

    // Get the authenticated user
    $user = Auth::user();

    // Update the user's name
   
    $user->name = $request->input('name');
    
    $user->email = $request->input('email');
   

    // Save the changes
    $user->save();

    // Return a simple JSON response with success message and updated user data
    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $user
    ], 200);
}

    
    
    
}
