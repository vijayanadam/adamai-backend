<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\CallLog;
class SmsController extends Controller
{
    /**
     * Send SMS using Twilio with a phone number (for trial accounts).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'to' => 'required|string',  // Ensure it's a valid phone number
            'message' => 'required|string|max:160', // SMS body should not exceed 160 characters
            'call_id' => 'required|integer',  // The call ID that will be updated
        ]);
    
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        // Retrieve the recipient phone number, message, and call ID from the request
        $to = $request->input('to');
        $message = $request->input('message');
        $callId = $request->input('call_id');
    
        // Retrieve Twilio credentials from .env
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');  // This is the Twilio phone number
    
        // Set the sender as the Twilio phone number for trial accounts
        $from = $twilioPhoneNumber;
    
        try {
            // Initialize Twilio client
            $client = new Client($accountSid, $authToken);
    
            // Send the SMS
            $sms = $client->messages->create(
                $to,  // Recipient's phone number
                [
                    'from' => $from,  // Use the Twilio phone number
                    'body' => $message, // The body of the SMS
                ]
            );
    
            // After sending SMS, update the status of the call to "Completed"
            $this->updateCallStatus($callId, 'Completed');  // Call the function to update call status
    
            // Return success response with the message SID
            return response()->json([
                'success' => true,
                'message_sid' => $sms->sid,
                'call_id' => $callId,  // Include the call ID in the response
            ]);
        } catch (\Twilio\Exceptions\RestException $e) {
            return response()->json(['error' => 'Twilio API error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage()], 500);
        }
    }
    // Update the status of the call
public function updateCallStatus($callId, $status)
{
    // Find the call by ID and update its status
    $call = CallLog::find($callId);  // Assuming 'Call' is the model for the call table

    if (!$call) {
        // If the call doesn't exist, return an error response
        return response()->json(['error' => 'Call not found'], 404);
    }

    // Update the call's status
    $call->status = $status; // Update the status to 'Completed' or any other value passed
    $call->save();  // Save the updated call record

    return response()->json([
        'success' => true,
        'message' => 'Call status updated to ' . $status,
    ]);
}

    
}
