<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Location;

class LocationController extends Controller
{
    public function checkLocation(Request $request)
    {
        $qrCode = $request->input('location_qr');

        if (!$qrCode) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $location = Location::select('location_id', 'zone_description', 'location_description', 'location_qr')
        ->leftJoin('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
        ->where('location_qr', $qrCode)->first();

        return response()->json($location);
    }
}


