<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HotPlayerService;

class HotPlayerController extends Controller
{
    public function checkDevice(Request $request)
    {
        $request->validate([
            'mac' => 'required|string',
            'source' => 'nullable|string',
        ]);

        $mac = $request->input('mac');
        $source = $request->input('source', 'main');

        // Validate MAC format first
        if (!HotPlayerService::isValidMacFormat($mac)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid MAC address format. Use format: XX:XX:XX:XX:XX:XX',
            ], 422);
        }

        $service = HotPlayerService::forSource($source);
        $result = $service->checkDevice($mac);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }
}
