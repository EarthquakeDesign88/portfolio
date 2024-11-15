<?php

namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Visitor;
use App\Models\Member;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\isEmpty;

class VisitorsController extends Controller
{
    public function insert(Request $request){
        $data = $request->all();
        $check = $data['parking_pass_type'];
        
        if($check == "1"){
            $member = Member::where('member_code', $data['parking_pass_code'])->first();
            if (!$member) {
                $data['msg'] = "ไม่พบชื่อสมาชิก";
                return response()->json(['status' => 'failed', 'errors' => $data], 404);
            }
        }
        
        $validator = Validator::make($request->all(), [
            'parking_pass_code' => 'required|string|unique:visitors,visitor_code',
            'parking_pass_type' => 'required|in:0,1'
        ],
        [
            'parking_pass_code.unique' => 'The :attribute has already been taken.',
            'parking_pass_type.in' => 'The :attribute field must be either 0 or 1.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->errors()], 422);
        }

        $date_now = Carbon::now();
        
        if($check == "0"){
            Visitor::create([
                'visitor_code'=> $data['parking_pass_code'], 
            ]);
        }

        $parkingRecord = ParkingRecord::create([
            'parking_pass_code'=> $data['parking_pass_code'], 
            'parking_pass_type'=> $data['parking_pass_type'],
            'carin_datetime'=> $date_now,
        ]);

        $parkingRecord->carin_datetime = Carbon::parse($parkingRecord->carin_datetime)->toDateTimeString();

        return Response()->json(['status' => 'success', 'data' => $parkingRecord]);
    }   
}
