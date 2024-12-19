<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CallLog;
use App\Models\Note;
use Illuminate\Support\Facades\Validator;

class CallViewController extends Controller

{
    public function callsview()
    {
        // Get the logged-in user ID
        $userId = auth()->id();
    
        // Fetch call logs for all phonebots belonging to the logged-in user, ordered by 'id' in descending order
        $callLogs = DB::table('call_logs')
            ->join('vbots', 'call_logs.phonebot_id', '=', 'vbots.id') // Join phonebot table
            ->where('vbots.user_id', $userId)   // Ensure the phonebot belongs to the logged-in user
            ->where('call_logs.status', '!=', 'Completed')
            ->orderBy('call_logs.id', 'desc')     // Order by 'id' in descending order
            ->select('call_logs.*', 'vbots.bezeichnung')               // Select all columns from call_logs
            ->get();
    
        // Count the number of records where status = 'New' and notification = 0 for the logged-in user's phonebots
        $newCallsCount = DB::table('call_logs')
            ->join('vbots', 'call_logs.phonebot_id', '=', 'vbots.id') // Join phonebot table
            ->where('vbots.user_id', $userId)    // Ensure the phonebot belongs to the logged-in user
            ->where('call_logs.status', 'New')      // Filter by status 'New'
            ->where('call_logs.notification', 0)    // Filter by notification = 0
            ->count();
    
        // Check if no call logs are found
        if ($callLogs->isEmpty()) {
            Log::warning('No call logs found for user_id: ' . $userId);
            return response()->json(['message' => 'No call logs found for your phonebots.'], 404);
        }
    
        Log::info('Returning call logs for user_id: ' . $userId);
    
        // Return the call logs along with the count of new calls
        return response()->json([
            'call_logs' => $callLogs,          // Return the call logs
            'new_calls_count' => $newCallsCount, // Count of new calls with notification = 0
        ], 200);
    }
    

    
    public function updateCallStatus(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
           'status' => 'required|string|max:255',
                
                
        ]);
    
        // Find the user by ID
        $call = CallLog::findOrFail($id);
    
        // Update the user's profile with the validated data
        $call->status = $request->input('status');
       
        
        
        // Save the changes
        $call->save();
    
        // Return a simple JSON response with success message and updated user data
        return response()->json([
            'message' => 'status updated successfully.',
            'user' => $call
        ], 200);
    }
    public function updateBulkStatus(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'calls' => 'required|array',  // Ensure 'calls' is an array
        'calls.*.id' => 'required|exists:call_logs,id',  // Each call must have an ID and exist in the database
        'calls.*.status' => 'required|string',  // Valid statuses
    ]);

    // Begin transaction to ensure atomic operation
    \DB::beginTransaction();

    try {
        // Loop through each call and update the status
        $updatedCalls = [];
        foreach ($request->input('calls') as $callData) {
            // Find the call by its ID
            $call = CallLog::findOrFail($callData['id']);
            
            // Update the status if necessary
            if ($call->status !== $callData['status']) {
                $call->status = $callData['status'];
                $call->save();  // Save the changes
            }

            // Store only the relevant updated fields for the response
            $updatedCalls[] = [
                'id' => $call->id,
                'status' => $call->status
            ];
        }

        // Commit the transaction
        \DB::commit();

        // Return a success response with updated call data
        return response()->json([
            'message' => 'Statuses updated successfully.',
            'updated_calls' => $updatedCalls
        ], 200);
    } catch (\Exception $e) {
        // Rollback transaction if anything goes wrong
        \DB::rollback();

        // Return error response with the exception message
        return response()->json([
            'message' => 'Error updating statuses.',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function getNotes($id)
    {
        
        $notes = DB::table('notes')
            ->where('call_id', $id)   
            ->orderBy('id', 'desc') 
            ->get();
    
      
        if ($notes->isEmpty()) {
            Log::warning('No notes found for call_id: ' . $id);
            return response()->json(['message' => 'No Notes found for this Call.'], 404);
        }
    
        Log::info('Returning notes for call_id: ' . $id);
        return response()->json($notes, 200); 
    }


    public function addNotes(Request $request,$id)
    {
        
        $validator = Validator::make($request->all(), [
            
            'text' => 'required|string|max:500', 
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        // Create the new note
        $note = Note::create([
            'call_id' => $id,
            'text' => $request->text,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Note added successfully!',
            'note' => $note
        ], 201);  // 201 Created
    }

    public function newCalls()
    {
        // Get the logged-in user ID
        $userId = auth()->id();
    
        // Fetch call logs for all phonebots belonging to the logged-in user, where status = 'New' and notification = 0
        $callLogs = DB::table('call_logs')
            ->join('vbots', 'call_logs.phonebot_id', '=', 'vbots.id') // Join phonebot table
            ->where('vbots.user_id', $userId)   // Ensure the phonebot belongs to the logged-in user
            ->where('call_logs.status', 'New')     // Filter by status 'New'
            ->where('call_logs.notification', 0)   // Filter by notification = 0
            ->orderBy('call_logs.id', 'desc')      // Order by 'id' in descending order
            ->select('call_logs.*', 'vbots.bezeichnung')               // Select all columns from call_logs
            ->get();
    
        // Count the number of new call logs for all phonebots belonging to the logged-in user
        $newCallsCount = DB::table('call_logs')
            ->join('vbots', 'call_logs.phonebot_id', '=', 'vbots.id') // Join phonebot table
            ->where('vbots.user_id', $userId)   // Ensure the phonebot belongs to the logged-in user
            ->where('call_logs.status', 'New')     // Filter by status 'New'
            ->where('call_logs.notification', 0)   // Filter by notification = 0
            ->count();
    
        // Check if no call logs are found
        if ($callLogs->isEmpty()) {
            Log::warning('No new call logs found for user_id: ' . $userId);
            return response()->json(['message' => 'No new call logs found for your phonebots.'], 404);
        }
    
        Log::info('Returning new call logs for user_id: ' . $userId);
    
        // Return the new call logs along with the count
        return response()->json([
            'call_logs' => $callLogs,          // Return the call logs
            'new_calls_count' => $newCallsCount, // Count of new calls with notification = 0
        ], 200);
    }
    
    public function updateNotificationStatus(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'calls' => 'required|array',  // Ensure 'calls' is an array
            'calls.*.id' => 'required',  // Each call must have an ID and exist in the database
        ]);
    
        // Begin transaction to ensure atomic operation
        \DB::beginTransaction();
    
        try {
            // Extract the call IDs from the request data
            $callIds = collect($request->input('calls'))->pluck('id');
    
            // Perform a bulk update to set 'notification' to 1 for the given call IDs
            $updatedCount = CallLog::whereIn('id', $callIds)
                ->update(['notification' => 1]);  // Update the notification field to 1
    
            // Check if any rows were actually updated
            if ($updatedCount > 0) {
                // Commit the transaction
                \DB::commit();
    
                // Return a success response with the number of updated rows
                return response()->json([
                    'message' => 'Statuses updated successfully.',
                    'updated_count' => $updatedCount,  // Optional: return the number of updated rows
                ], 200);
            } else {
                // If no rows were updated, throw an exception
                throw new \Exception('No calls were updated.');
            }
    
        } catch (\Exception $e) {
            // Rollback transaction if anything goes wrong
            \DB::rollback();
    
            // Return error response with the exception message
            return response()->json([
                'message' => 'Error updating statuses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateCallStatusCompleted(Request $request, $id)
    {
       
    
        // Find the user by ID
        $call = CallLog::findOrFail($id);
    
        // Update the user's profile with the validated data
        $call->status = 'Completed';
       
        
        
        // Save the changes
        $call->save();
    
        // Return a simple JSON response with success message and updated user data
        return response()->json([
            'message' => 'status updated successfully.',
            'user' => $call
        ], 200);
    }
    

}
