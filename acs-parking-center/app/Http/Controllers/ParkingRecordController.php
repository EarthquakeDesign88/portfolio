<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingRecord;
use App\Models\Member;
use App\Models\PaymentTransaction;
use App\Models\Stamp;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Exception;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ParkingRecordController extends Controller
{

    public function index()
    {
        $currentDate = Carbon::now()->toDateString();
        $parkingRecords = ParkingRecord::whereDate('carin_datetime', $currentDate)->orderBy('carin_datetime', 'desc')->get();
        return response()->json($parkingRecords);
    }

    public function getParkingOut()
    {
        $currentDate = Carbon::now()->toDateString();
        $parkingRecords = DB::table('parking_records as pr')
            ->join('payment_transactions as pt', 'pr.id', '=', 'pt.parking_record_id')
            ->select('pr.*', 'pt.*')
            ->whereDate('carout_datetime', $currentDate)->orderBy('carout_datetime', 'desc')
            ->where('pt.payment_status', "1")
            ->get();

        return response()->json($parkingRecords);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $stamp = null;
        try {

            // $parkingRecord = ParkingRecord::firstWhere('parking_pass_code', $data['parking_pass_code'])->latest()->first();
            $parkingRecord = ParkingRecord::where('parking_pass_code', $data['parking_pass_code'])->orderBy('id', 'desc')->first();

            if (!$parkingRecord) {
                $data['msg'] = "ไม่พบข้อมูล";
                return response()->json(['status' => 'failed', 'errors' => $data], 404);
            }

            if ($parkingRecord['carout_datetime'] != null && $parkingRecord['parking_pass_type'] == "0") {
                $data['msg'] = "บัตรเลขนี้ชำระเงินแล้ว";
                return response()->json(['status' => 'failed', 'errors' => $data], 400);
            }

            if ($parkingRecord['carout_datetime'] != null && $parkingRecord['parking_pass_type'] == "1") {
                $data['msg'] = "ยังไม่มีการใช้บัตรรถเข้า";
                return response()->json(['status' => 'failed', 'errors' => $data], 400);
            }
            // if ($parkingRecord['0']['parking_pass_type'] == "0") {
            //     $response = Http::get('https://acs-groups.com/backoffice/parking-service/api/stamp/check_stamp_qty/' . $data['parking_pass_code']);
            //     if (!$response->successful()) {
            //         $data['msg'] = "เกิดข้อผิดพลาดเกี่ยวกับเซิฟเวอร์";
            //         return response()->json(['status' => 'failed', 'errors' => $data], 500);
            //     }
            //     $stamp = $response->json();
            // }

            $stamp = Stamp::where('stamp_condition', "จอดฟรี ไม่ต้องสรุปตราประทับ")
                ->orWhere('stamp_condition', "ปล่อยผ่าน คิดเงินสิ้นเดือน")
                ->get();

            $file_name = $parkingRecord['license_plate_path'];
            $folder_file = $parkingRecord['carin_datetime'];
            $folder = explode(" ", $folder_file);
            $file_origi = explode("/", $file_name);
            $file_path = 'public/images/crop/' . $folder[0] . '/' . $file_origi[count($file_origi) - 1];

            if (Storage::exists($file_path)) {
                $file = Storage::get('public/images/crop/' . $folder[0] . '/' . $file_origi[count($file_origi) - 1]);
            } else {
                $file = Storage::get('public/images/origi/' . $folder[0] . '/' . $file_origi[count($file_origi) - 1]);
            }

            $base64Image = base64_encode($file);

            $parkingRecord['carout_datetime'] = $currentDate;
            $parkingRecord['license_plate_path'] = $base64Image;
            $parkingRecord['stamp'] = $stamp;

            return Response()->json(['status' => 'success', 'data' => $parkingRecord]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed_4000', 'errors' => $e], 400);
        }
    }

    public function insert(Request $request)
    {
        $data = $request->all();
        $date_now = Carbon::now();
        $newFileName = 'image_' . $date_now->format('Ymd_His') . '.jpg';
        $license_text = isset($data['license_plate']) ? $data['license_plate'] : null;
        $url = null;

        $validator = Validator::make(
            $request->all(),
            [
                'parking_pass_code' => 'required',
                'parking_pass_type' => 'required|in:0,1'
            ],
            [
                'parking_pass_code.required' => 'กรุณากรอกรหัสโค๊ด',
                'parking_pass_type.required' => 'กรุณากรอกประเภทสมาชิก',
                'parking_pass_type.in' => 'กรุณาใส่เลข 0 , 1',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed_4001', 'errors' => $validator->errors()], 422);
        }   

        $check = $data['parking_pass_type'];

        $check_record = ParkingRecord::where('parking_pass_code', $data['parking_pass_code'])
            ->where('parking_pass_type', $check)
            ->where('carout_datetime', null)
            ->first();

        if ($check_record) {
            $data['msg'] = "คุณยังไม่ได้เอารถออก";
            return response()->json(['status' => 'failed_4004', 'errors' => $data], 400);
        }

        if ($check == "1") {
            $member = Member::join('companies', 'members.company_id', '=', 'companies.id')
                ->join('places', 'members.place_id', '=', 'places.id')
                ->join('member_types', 'members.member_type_id', '=', 'member_types.id')
                ->where('member_code', $data['parking_pass_code'])
                ->first();

            if (!$member) {
                $data['msg'] = "ไม่พบชื่อสมาชิก";
                return response()->json(['status' => 'failed_4002', 'errors' => $data], 404);
            }

            if ($member['member_status'] == "inactive") {
                return response()->json(['status' => 'failed_4003', 'errors' => $member], 400);
            }

            $date = $date_now->toDateString();
            $date_timestamp = strtotime($date);
            $exprie_timestamp = strtotime(explode(" ", $member['expiry_date'])[0]);

            if ($date_timestamp > $exprie_timestamp) {
                $data['msg'] = "บัตรสมาชิกหมดอายุ";
                return response()->json(['status' => 'failed_4005', 'errors' => $data], 400);
            }

            $parkingRecord['member'] = $member;
        }

        $currentDate = Carbon::now()->format('Y-m-d');

        if ($request->hasFile('origi_license')) {
            $license_origi = $request->file('origi_license');
            $path = $license_origi->storeAs('public/images/origi/' . $currentDate , $newFileName);
            $url = Storage::url($path);
        }

        if ($request->hasFile('crop_license')) {
            $license = $request->file('crop_license');
            $path = $license->storeAs('public/images/crop/' . $currentDate , $newFileName);
            $url = Storage::url($path);
        }

        $parkingRecord['parking_record'] = ParkingRecord::create([
            'parking_pass_code' => $data['parking_pass_code'],
            'parking_pass_type' => $data['parking_pass_type'],
            'license_plate' => $license_text,
            'license_plate_path' => $url,
            'carin_datetime' => $date_now->toDateTimeString(),
        ]);

        return Response()->json(['status' => 'success', 'data' => $parkingRecord], 201);
    }


    //For Test API 
    public function insertServer(Request $request)
    {
        $data = $request->all();
        $date_now = Carbon::now();

        $parkingRecord = new ParkingRecord;
        $parkingRecord->parking_pass_code = $data['parking_pass_code'];
        $parkingRecord->parking_pass_type = $data['parking_pass_type'];
        $parkingRecord->license_plate = $data['license_plate'];
        $parkingRecord->license_plate_path = $data['license_plate_path'];
        $parkingRecord->stamp_id = $data['stamp_id'];
        $parkingRecord->stamp_qty = $data['stamp_qty'] == null ? 0 : $data['stamp_qty'];
        $parkingRecord->carin_datetime = $date_now;

        $parkingRecord->save();

        return response()->json(['status' => 'success', 'data' => $parkingRecord], 201);
    }

    public function recordParking(Request $request)
    {
        $selectedDate = $request->input('selectedDate') != '' ? $request->input('selectedDate') : date('Y-m-d');

        if ($request->ajax()) {
            $query = DB::table('parking_records')
                ->leftJoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id')
                ->select('parking_records.*', 'stamps.stamp_code')
                ->where('parking_records.parking_pass_type', '=', '0')
                ->whereDate('parking_records.carin_datetime', $selectedDate)
                ->orderBy('parking_records.id', 'desc');

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($query) use ($search) {
                    $query->where('parking_records.parking_pass_code', 'like', "%{$search}%")
                      ->orWhere('stamps.stamp_code', 'like', "%{$search}%");
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        $paymentMethods = PaymentMethod::all();
        return view('car.record-parking', compact('paymentMethods'));
    }



    public function getRecordParkings(Request $request)
    {
        $selectedDate = $request->input('selectedDate') != '' ? $request->input('selectedDate') : date('Y-m-d');

        $recordParkings = DB::table('parking_records')
            ->leftjoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id')
            ->select('parking_records.*', 'stamps.stamp_code')
            ->where('parking_records.parking_pass_type', '=', '0')
            ->whereDate('parking_records.carin_datetime', $selectedDate)
            ->orderBy('parking_records.id', 'desc')
            ->get();


        $countRecordParkings = $recordParkings->count();

        return response()->json([
            'recordParkings' => $recordParkings,
            'countRecordParkings' => $countRecordParkings
        ]);
    }

    public function insertRecordParking(Request $request)
    {
        $request->validate(
            [
                'parking_pass_code' => 'required',
                'license_plate' => 'required',
                'stamp_id' => 'required',
                'stamp_qty' => 'required',
                'carin_datetime' => 'required',
                'carout_datetime' => 'required',
                'payment_method_id' => 'required',
                'fee' => 'required',
            ],
            [
                'parking_pass_code.required' => 'กรุณากรอกรหัสบัตรจอดรถ',
                'license_plate.required' => 'กรุณากรอกป้ายทะเบียนรถ',
                'stamp_id.required' => 'กรุณาเลือกรหัสตราประทับ',
                'stamp_qty.required' => 'กรุณากรอกจำนวนตราประทับ',
                'carin_datetime.required' => 'กรุณาเลือกเวลาเข้า',
                'carout_datetime.required' => 'กรุณาเลือกเวลาออก',
                'payment_method_id.required' => 'กรุณาเลือกวิธีชำระเงิน',
                'fee.required' => 'กรุณากรอกยอดชำระเงิน',
            ]
        );

        try {
            $data = $request->all();

            $existingRecord = ParkingRecord::where('parking_pass_code', $data['parking_pass_code'])->first();

            if ($existingRecord) {
                return Response()->json([
                    'status' => 'error',
                    'message' => 'รหัสบัตรจอดรถนี้มีอยู่แล้ว กรุณาใช้รหัสอื่น'
                ]);
            }

            $data['carin_datetime'] = date('Y-m-d H:i:s', strtotime($data['carin_datetime']));
            $data['carout_datetime'] = date('Y-m-d H:i:s', strtotime($data['carout_datetime']));

            $parkingRecord = new ParkingRecord();
            $parkingRecord->parking_pass_code = $data['parking_pass_code'];
            $parkingRecord->parking_pass_type = '0';
            $parkingRecord->license_plate = $data['license_plate'];
            $parkingRecord->license_plate_path = 'null';
            $parkingRecord->stamp_id = $data['stamp_id'];
            $parkingRecord->stamp_qty = $data['stamp_qty'];
            $parkingRecord->carin_datetime = $data['carin_datetime'];
            $parkingRecord->carout_datetime = $data['carout_datetime'];
            $parkingRecord->added_manually = true;

            $parkingRecord->save();


            //Save Payment Transaction
            $paymentTransaction = new PaymentTransaction();
            $paymentTransaction->payment_status = '1';
            $paymentTransaction->payment_method_id = $data['payment_method_id'];
            $paymentTransaction->paid_datetime = $data['carout_datetime'];
            $paymentTransaction->fee = $data['fee'];
            $paymentTransaction->parking_record_id = $parkingRecord->id;

            $paymentTransaction->save();

            $response = [
                'status' => 'success',
                'message' => 'บันทึกบัตรจอดรถสำเร็จ',
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง',
                'error' => $e->getMessage()
            ];
        }

        return Response()->json($response);
    }

    public function getParkingRecordById($id)
    {
        try {
            $parkingRecord = ParkingRecord::query()
                ->leftjoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id')
                ->leftJoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id')
                ->select('parking_records.*', 'stamps.stamp_code', 'payment_transactions.id as payment_transaction_id', 'payment_transactions.payment_method_id', 'payment_transactions.fee')
                ->where('parking_records.id', $id)
                ->firstOrFail();


            $response = [
                'status' => 'success',
                'message' => 'ดึงข้อมูลบัตรจอดรถสำเร็จ',
                'parkingRecord' => $parkingRecord
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลบัตรจอดรถนี้ในระบบ',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 404);
        }

        return response()->json($response);
    }


    public function updateRecordParking(Request $request, $id)
    {
        $parkingRecord = ParkingRecord::find($id);

        if (!$parkingRecord) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบรหัสบัตรจอดรถนี้ในระบบ'
            ];
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'parking_pass_code' => 'required',
                'license_plate' => 'required',
                'stamp_id' => 'required',
                'stamp_qty' => 'required',
                'carin_datetime' => 'required',
                'carout_datetime' => 'required',
                'payment_method_id' => 'required',
                'fee' => 'required',
            ],
            [
                'parking_pass_code.required' => 'กรุณากรอกรหัสบัตรจอดรถ',
                'license_plate.required' => 'กรุณากรอกป้ายทะเบียนรถ',
                'stamp_id.required' => 'กรุณาเลือกรหัสตราประทับ',
                'stamp_qty.required' => 'กรุณากรอกจำนวนตราประทับ',
                'carin_datetime.required' => 'กรุณาเลือกเวลาเข้า',
                'carout_datetime.required' => 'กรุณาเลือกเวลาออก',
                'payment_method_id.required' => 'กรุณาเลือกวิธีชำระเงิน',
                'fee.required' => 'กรุณากรอกยอดชำระเงิน',
            ]
        );


        try {
            $data = $request->all();

            $data['carin_datetime'] = date('Y-m-d H:i:s', strtotime($data['carin_datetime']));
            $data['carout_datetime'] = date('Y-m-d H:i:s', strtotime($data['carout_datetime']));

            $parkingRecord->update([
                'parking_pass_code' => $data['parking_pass_code'],
                'parking_pass_type' => '0',
                'license_plate' => $data['license_plate'],
                'stamp_id' => $data['stamp_id'],
                'stamp_qty' => $data['stamp_qty'],
                'carin_datetime' => $data['carin_datetime'],
                'carout_datetime' => $data['carout_datetime'],
                'added_manually' => true,
            ]);

            $paymentTransaction = PaymentTransaction::updateOrCreate(
                ['parking_record_id' => $parkingRecord->id],
                [
                    'payment_method_id' => $data['payment_method_id'],
                    'paid_datetime' => $data['carout_datetime'],
                    'fee' => $data['fee'],
                    'payment_status' => '1',
                ]
            );

            $response = [
                'status' => 'success',
                'message' => 'แก้ไขบัตรจอดรถเรียบร้อยแล้ว',
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด',
                'error' => $e->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function historyRecordManual(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('parking_records')
                ->leftjoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id')
                ->leftjoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id')
                ->where('added_manually', '=', 1)
                ->orderBy('parking_records.id', 'desc')
                ->get())
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('car.history-record-manual');
    }


    public function getHistoryRecordManual()
    {
        $recordParkings = DB::table('parking_records')
            ->leftjoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id')
            ->leftjoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id')
            ->where('added_manually', '=', 1)
            ->orderBy('parking_records.id', 'desc')
            ->get();

        $countRecordParkings = $recordParkings->count();

        return response()->json([
            'recordParkings' => $recordParkings,
            'countRecordParkings' => $countRecordParkings
        ]);
    }
}
