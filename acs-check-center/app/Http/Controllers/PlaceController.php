<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    public $data = array();

    public function zones()
    {
        $zones = Zone::whereNull('deleted_at')->paginate(10);
        return view('content.place.zone', compact('zones'));
    }

    public function zone_get(Request $request)
    {
        $id = $request->route('id');

        if ($id !== null && !empty($id)) {
            $zone = Zone::where('zone_id', $id)->first();
            return view('content.place.zone_edit', compact('zone'));
        } else {
            return redirect()->back();
        }
    }

    public function zone_update(Request $request, $id)
    {

        if (empty($request->input('zone_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดโซน']);
        }

        try {
            $count = DB::table('zones as z')
                ->where('z.zone_description', '=', $request->input('zone_desc'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดโซนที่ซ้ำ']);
            }

            $zone = Zone::findOrFail($id);
            $zone->zone_description = $request->input('zone_desc');
            $zone->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทโซนสำเร็จ']);
            
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function zone_create(Request $request)
    {

        if (empty($request->input('zone_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดโซน']);
        }

        try {
            $count = DB::table('zones as z')
                ->where('z.zone_description', '=', $request->input('zone_desc'))
                ->whereNull('z.deleted_at')
                ->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดโซนที่ซ้ำ']);
            }

            $zone = new Zone;
            $zone->zone_description = $request->input('zone_desc');
            $zone->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างโซนสำเร็จ']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function locations(Request $request)
    {
        $query = DB::table('locations as l')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->where('l.deleted_at', '=', null);

        if ($request->filled('location_search')) {
            $query->where('l.location_id', $request->input('location_search'));
        }

        if ($request->filled('location_zone_search')) {
            $query->where('z.zone_id', $request->input('location_zone_search'));
        }

        //Data Table
        $query->select('l.*', 'z.*');
        $locations = $query->orderBy('l.location_id', 'ASC')->paginate(10);


        //QR Code 
        $locationQR = DB::table('locations as l')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id');

        if ($request->filled('location_search')) {
            $locationQR->where('l.location_id', $request->input('location_search'));
        }

        if ($request->filled('location_zone_search')) {
            $locationQR->where('z.zone_id', $request->input('location_zone_search'));
        }

        $locationQR = $locationQR->select('l.*', 'z.*')->orderBy('l.location_id', 'ASC')->get();

        $zones = Zone::where('zone_status', '1')->whereNull('deleted_at')->get();

        $locations_search = DB::table('locations as l')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->whereNull('z.deleted_at')
            ->whereNull('l.deleted_at');

        $locations_search = $locations_search->get();

        session(['locations' => $locationQR]);

        return view('content.place.location', compact("locations", "zones", "locations_search"));
    }

    public function report_location()
    {
        $data = session('locations', []);

        $pdf = Pdf::loadView('exports.pdf', ["data" => $data]);
        return $pdf->download('location.pdf');
    }


    public function location_create(Request $request)
    {

        if (empty($request->input('location_description'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดสถานที่']);
        }

        if (empty($request->input('location_zone'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกโซน']);
        }

        $uniqueString = Str::random(10);

        try {
            $count = DB::table('locations as l')
                ->where('l.location_description', '=', $request->input('location_description'))
                ->where('l.location_zone_id', $request->input('location_zone'))
                ->whereNull('l.deleted_at')
                ->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดสถานที่และโซนซ้ำ']);
            }

            $qr = $uniqueString . '_' . 'QR';

            $qrCode = QrCode::size(150)->generate($qr);
            $fileName = 'qr_' . $qr . '.svg';
            Storage::put('public/qr_codes/' . $fileName, $qrCode);

            $location = new Location;
            $location->location_description = $request->input('location_description');
            $location->location_qr = $qr;
            $location->location_zone_id = $request->input('location_zone');
            $location->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างจุดตรวจสำเร็จ']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function location_get(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $zones = $zones = Zone::where('zone_status', '1')->whereNull('deleted_at')->get();
        return view('content.place.location_edit', compact('location', 'zones'));
    }

    public function location_del(Request $request)
    {
        try {
            $id = $request->input("id");
            $location = Location::findOrFail($id);

            $check = DB::table('locations as l')
                ->join('user_authorities as ua', 'l.location_id', '=', 'ua.user_location_id')
                ->where('l.location_id', $location->location_id)
                ->where('ua.user_authority_status', '1')
                ->whereNull('ua.deleted_at')
                ->count();

            if ($check) {
                return response()->json(['status' => 'info', 'message' => 'กรุณายกเลิกสิทธิการตรวจก่อน']);
            }

            if ($location) {

                DB::beginTransaction();

                try {

                    DB::table('locations')
                        ->where('location_id', $location->location_id)
                        ->update([
                            'location_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    DB::table('user_authorities')
                        ->where('user_location_id', $location->location_id)
                        ->update([
                            'user_authority_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function location_update(Request $request, $id)
    {

        if (empty($request->input('location_description'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดสถานที่']);
        }

        if (empty($request->input('location_zone'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกโซน']);
        }

        $uniqueString = Str::random(10);

        try {
            $count = DB::table('locations as l')
                ->where('l.location_description', '=', $request->input('location_description'))
                ->where('l.location_zone_id', $request->input("location_zone"))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดสถานที่และโซนซ้ำ']);
            }

            $location = Location::findOrFail($id);

            $qr = $uniqueString . '_' . 'QR';

            $qrCode = QrCode::size(150)->generate($qr);
            $fileName = 'qr_' . $qr . '.svg';
            Storage::put('public/qr_codes/' . $fileName, $qrCode);

            $location->location_description = $request->input('location_description');
            $location->location_qr = $qr;
            $location->location_zone_id = $request->input("location_zone");
            $location->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทจุดตรวจสำเร็จ']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function location_status(Request $request)
    {

        if (empty($request->input('id'))) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลสถานะที่ใช้']);
        }

        try {
            $id = explode("_", $request->input('id'))[1];
            $location = Location::findOrFail($id);
            $status = $request->input('status') == '0' ? '1' : '0';

            if ($location) {

                DB::beginTransaction();

                try {

                    DB::table('locations')
                        ->where('location_id', $location->location_id)
                        ->update([
                            'location_status' => $status,
                        ]);

                    DB::table('user_authorities')
                        ->where('user_location_id', $location->location_id)
                        ->update([
                            'user_authority_status' => '0',
                            'deleted_at' => now()
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'เปิดใช้งานสำเร็จ', 'location-status' => $status]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function zone_status(Request $request)
    {
        if (empty($request->input('id'))) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลสถานะที่ใช้']);
        }

        try {
            $id = explode("_", $request->input('id'))[1];
            $zone = Zone::findOrFail($id);
            $status = $request->input('status') == '0' ? '1' : '0';

            if ($zone) {

                DB::beginTransaction();

                try {

                    DB::table('zones')
                        ->where('zone_id', $zone->zone_id)
                        ->update([
                            'zone_status' => $status,
                        ]);

                    DB::table('locations')
                        ->where('location_zone_id', $zone->zone_id)
                        ->update([
                            'location_status' => $status,
                        ]);

                    $locationIds = DB::table('locations')
                        ->where('location_zone_id', $zone->zone_id)->first();

                    DB::table('user_authorities')
                        ->where('user_location_id', $locationIds->location_id)
                        ->update([
                            'user_authority_status' => '0',
                            'deleted_at' => now()
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'เปิดใช้งานสำเร็จ', 'zone-status' => $status]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function zone_del(Request $request)
    {
        try {
            $id = $request->input("id");
            $zone = Zone::findOrFail($id);

            if ($zone) {

                DB::beginTransaction();

                try {

                    DB::table('zones')
                        ->where('zone_id', $zone->zone_id)
                        ->update([
                            'zone_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    DB::table('locations')
                        ->where('location_zone_id', $zone->zone_id)
                        ->update([
                            'location_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    $locationIds = DB::table('locations')
                        ->where('location_zone_id', $zone->zone_id)->get();

                    DB::table('user_authorities')
                        ->where('user_location_id', $locationIds->location_id)
                        ->update([
                            'user_authority_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }
}
