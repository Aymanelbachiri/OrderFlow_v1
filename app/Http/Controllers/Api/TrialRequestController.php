<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrialRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'requestId' => 'required|string|unique:trial_requests,request_id',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'server' => 'nullable|string',
            'serverType' => 'nullable|string',
            'trialDuration' => 'nullable|string',
            'hasWhatsapp' => 'nullable|boolean',
            'requestedCountries' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $trialRequest = TrialRequest::create([
            'request_id' => $request->input('requestId'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'country' => $request->input('country'),
            'server' => $request->input('server'),
            'server_type' => $request->input('serverType'),
            'trial_duration' => $request->input('trialDuration'),
            'has_whatsapp' => $request->input('hasWhatsapp', false),
            'requested_countries' => $request->input('requestedCountries'),
            'source' => $request->input('source'),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trial request submitted successfully',
            'data' => [
                'id' => $trialRequest->id,
                'request_id' => $trialRequest->request_id,
                'status' => $trialRequest->status,
            ]
        ], 201);
    }
}
