<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Stamp;
use App\Models\Floor;
use App\Models\Place;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{
    public function searchCompanies(Request $request)
    {
        $keyword = $request->input('company_name');

        $companies = Company::where('company_name', 'like', '%' . $keyword . '%')->get();


        return response()->json($companies);
    }
    
    public function searchStamps(Request $request)
    {
        $keyword = $request->input('stamp_code');

        $stamps = Stamp::where('stamp_code', 'like', '%' . $keyword . '%')->get();


        return response()->json($stamps);
    }

    public function searchFloors(Request $request)
    {
        $keyword = $request->input('floor_number');

        $floors = Floor::where('floor_number', 'like', '%' . $keyword . '%')->get();


        return response()->json($floors);
    }

    public function searchPlaces(Request $request)
    {
        $keyword = $request->input('place_name');

        $places = Place::where('place_name', 'like', '%' . $keyword . '%')->get();


        return response()->json($places);
    }

}
