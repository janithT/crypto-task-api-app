<?php
// Resusable helper functions

use App\Mail\SystemMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


// Reusable API response function

function apiResponseWithStatusCode($data, $status, $message, $user, $statusCode)
{
    $response = [];
    $response['status'] = $status;
    $response['message'] = $message;
    if ($user) {
        $response['user'] = $user;
    }
    $response['data'] = $data;
    // if ($token) {
    //     $response['token'] = $token;
    // } 
    return  response()->json($response, $statusCode);
    
}

// generate key for system use
function generateTaskKey($perfix): string
{
    return $perfix . Str::random(10);
}

// send emails

function sendSystemEmail(array $data = [])
{
    // if data is exists
    if (!empty($data) && isset($data['to'])) {
        try {
            Mail::to($data['to'])->queue(new SystemMail($data));
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome email sending failed: ' . $e->getMessage());

            return false;
        }
    }
    return false;

}