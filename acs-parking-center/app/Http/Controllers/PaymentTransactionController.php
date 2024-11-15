<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\ParkingRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Stamp;
use App\Models\CompanySetup;

class PaymentTransactionController extends Controller
{
    public function insert(Request $request){
        $data = $request->all();
        $data['currentDate'] = Carbon::now();

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:0,1',
            'parking_record_id' => 'required'
        ],
        [
            'payment_status.required' => 'The payment status is required.',
            'parking_record_id.required' => 'The payment status is required.',
            'payment_status.in' => 'The :attribute field must be either 0 or 1.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->errors()], 422);
        }
        
        if(isset($data['stamp_id'])){
            $stamp = Stamp::where('stamp_code', $data['stamp_id'])->get()->first();
            if(!$stamp){
                $data['msg'] = "ไม่พบข้อมูลรหัสตราประทับ";
                return Response()->json(['status' => 'failed_4001', 'errors' => $data], 404);
            }

        }else{
            $stamp['id'] = null;
            $stamp['stamp_qty'] = null;
        }

        ParkingRecord::where('id', $data['parking_record_id'])->update([
            'carout_datetime' => $data['carout_datetime'],
            'stamp_id' => $stamp['id'],
            'stamp_qty' => $data['stamp_qty']
        ]);
    
        $payment = PaymentTransaction::create([
            'payment_status'=> $data['payment_status'],
            'payment_method_id'=> $data['payment_method_id'],  
            'paid_datetime'=> $data['currentDate'], 
            'fee'=> $data['fee'],
            'parking_record_id'=> $data['parking_record_id'],
        ]);

        return Response()->json(['status' => 'success', 'data' => $payment]);
    }
}
