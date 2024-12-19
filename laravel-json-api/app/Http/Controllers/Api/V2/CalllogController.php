<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Vbot;
use App\Models\CallLog;
use Carbon\Carbon;
use App\Models\User;
class CalllogController extends Controller
{
    public function fetchPhonebotSettings(Request $request, $user_id)
    {
        // Fetch phonebots associated with the user by license_key
        $phonebots = Vbot::where('license_Key', $user_id)->get();
    
        // If no phonebots found, return an error
        if ($phonebots->isEmpty()) {
            return response()->json(['error' => 'No phonebots found for this license_Key.'], 404);
        }
    
        // Prepare the data in the desired format
        $returnArr = $phonebots->map(function ($vbot) {
            return [
                'phonebot_id' => $vbot->id,
                'SIP-Benutzername' => $vbot->sip_benutzername,
                'SIP-Registername' => $vbot->sip_registername,  
                'SIP-Passwort' => $vbot->sip_passwd,
                'SIP-Server' => $vbot->sip_server,
                'SIP-Number' => $vbot->sip_phonenumber,
                'SIP-Port' => $vbot->sip_port,
                'Attendedtransfer' => $vbot->attendedtransfer == 1 ? 'true' : 'false',
                'TTS_service' => $vbot->ttsmodel, 
                'STT_service' => $vbot->sttmodel, 
                'Mail_Certifikate_Validation' => $vbot->mail_certifikate_validation,
                'OpenAI-ApiKey' => $vbot->apen_ai_key,
                'ChatModel' => $vbot->chatmodel,
                'ChatModelApi' => $vbot->chatmodel_api,
                'STTModelApi' => $vbot->sttmodel_api,
                'TTSModelApi' => $vbot->ttsmodel_api,
                'Displayname' => $vbot->bezeichnung, 
                'Productname' => $vbot->product_name,
                'Assistant-Description' => $vbot->prompt, 
                'Assistant-Intro' => $vbot->ansagetext, 
                'PromptXT-URL' => $vbot->prompturl,
                'TransportType' => $vbot->transmit_type,
                'STUN_Server' => $vbot->stun_server, 
                'mailing' => [
                    'host' => $vbot->e_mail_server,
                    'username' => $vbot->e_mail_user,
                    'password' => $vbot->e_mail_passwd,
                    'port' => $vbot->e_mail_server_port,
                    'from' => $vbot->e_mail_server_from,
                    'mailingList' => [
                        ['number' => '', 'email' => ''],
                        ['number' => '', 'email' => '']
                    ]
                ]
            ];
        });
    
        // Convert the data to JSON (plaintext)
        $plaintext = json_encode($returnArr);
    
        // Get the password from the GET request (userData)
        $password = $user_id;
    
        // Define encryption method
        $method = 'aes-256-cbc';
    
        // Generate a 32-byte key from the password using SHA256
        $key = substr(hash('sha256', $password, true), 0, 32);
    
        // Define the IV (Initialization Vector), hardcoded as zeros
        $iv = str_repeat(chr(0x0), 16); // 16-byte IV filled with zeros
    
        // Encrypt the data using openssl_encrypt
        $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    
        // Encode the encrypted data in base64
        $ciphertextBase64 = base64_encode($ciphertext);
    
        // Return the encrypted response
        return response()->json([
            'message' => 'Phonebot settings retrieved successfully.',
            'data' => $ciphertextBase64
        ], 200);
    }
    
    
    /**
     * Store call logs for the phonebots of the given user.
     *
     * @param Request $request
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCallLogs(Request $request, $phonebot_id)
    {
        // Validate the incoming call logs data
        $validator = Validator::make($request->all(), [
            'call_logs' => 'required|array',
            'call_logs.*.caller_number' => 'nullable|string',
            'call_logs.*.text' => 'required|string',
            'call_logs.*.action' => 'required|string',
        ]);
    
        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }
    
        // Fetch the phonebot associated with the provided ID
        $phonebot = Vbot::find($phonebot_id);
    
        // If no phonebot is found, return an error
        if (!$phonebot) {
            return response()->json(['error' => 'No phonebot found for this id.'], 404);
        }
    
        // Group all logs into a single entry
        $callerNumber = null;
        $logDetails = [];
        $priority = 'Normal Callback'; // Default priority
    
        foreach ($request->call_logs as $log) {
            // Capture caller_number from the first log entry
            if ($callerNumber === null) {
                $callerNumber = $log['caller_number'] ?? null;
            }
    
            // Determine priority based on text or action
            $text = strtolower($log['text']);
            $action = strtolower($log['action']);
    
            if (preg_match('/\burgent\b|\bimportant\b|\bquick\b/', $text) || preg_match('/\burgent\b|\bimportant\b|\bquick\b/', $action)) {
                $priority = 'Important Callback';
            } elseif (preg_match('/\bemail\b|\bsend email\b/', $text) || preg_match('/\bemail\b|\bsend email\b/', $action)) {
                $priority = 'Send Email';
            } elseif (preg_match('/\btransfer\b|\btransferred\b/', $text) || preg_match('/\btransfer\b|\btransferred\b/', $action)) {
                $priority = 'Transferred';
            }
            
    
            // Add each text-action pair to the logDetails array
            $logDetails[] = [
                'text' => $log['text'],
                'action' => $log['action'],
            ];
        }
    
        // Save the consolidated call log with log details as JSON
        CallLog::create([
            'phonebot_id' => $phonebot_id,
            'caller_number' => $callerNumber,
            'response' => json_encode($logDetails), // Save as JSON
            'priority' => $priority,               // Set dynamically based on text/action
            'status' => 'New',
            'notification' => 0,
        ]);
    
        // Return a success response
        return response()->json([
            'message' => 'Call logs successfully stored.',
        ], 200);
    }
    
    
    
    
    
    public function getCalls(Request $request)
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Fetch all phonebots associated with the logged-in user
        $phonebots = Vbot::where('user_id', $user->id)->get();
    
        // If no phonebots are found, return an error response
        if ($phonebots->isEmpty()) {
            return response()->json(['error' => 'No phonebots found for the authenticated user.'], 404);
        }
    
        // Fetch the call logs for all phonebots, order them by created_at in descending order, and include the phonebot name (bezeichnung)
        $callLogs = CallLog::whereIn('phonebot_id', $phonebots->pluck('id'))
            ->with('phonebot')  // Eager load the associated phonebot
            ->orderByDesc('id')  // Order the call logs by created_at in descending order
            ->get();
    
        // Transform the call logs to include the phonebot name and format the date fields
        $callLogsWithPhonebotName = $callLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'phonebot_id' => $log->phonebot_id,
                'caller_number' => $log->caller_number,
                'text' => $log->text,
                'action' => $log->action,
                'phonebot_name' => $log->phonebot ? $log->phonebot->bezeichnung : null, // Add the phonebot name
                'created_at' => Carbon::parse($log->created_at)->format('Y-m-d H:i:s'), // Format created_at
                'updated_at' => Carbon::parse($log->updated_at)->format('Y-m-d H:i:s'), // Format updated_at
            ];
        });
    
        // Return the call logs with phonebot names and formatted dates
        return response()->json([
            'message' => 'Call logs retrieved successfully.',
            'data' => $callLogsWithPhonebotName
        ], 200);
    }
    
public function deleteCalls($id)
{
    // Find the Vbot
    $vbot = CallLog::findOrFail($id);

    // Delete the Vbot instance
    $vbot->delete();

    return response()->json(['message' => 'Vbot deleted successfully!']);
}
    
}
