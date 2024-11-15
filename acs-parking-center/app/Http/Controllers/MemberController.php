<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Resources\MemberResource;
use App\Models\MemberType;
use App\Models\Place;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ExportMemberList;

class MemberController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $members = DB::table('members')
                ->join('companies', 'members.company_id', '=', 'companies.id')
                ->join('member_types', 'members.member_type_id', '=', 'member_types.id')
                ->join('places', 'members.place_id', '=', 'places.id')
                ->select('members.*', 'companies.company_name', 'member_types.member_type', 'places.place_name')
                ->orderBy('members.id', 'desc')
                ->get();

            return DataTables::of($members)
                ->addIndexColumn()
                ->addColumn('member_name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company_name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $memberTypes = DB::table('member_types')->orderBy('id', 'DESC')->get();
        $places = DB::table('places')->orderBy('id', 'DESC')->get();

        return view('member.member-list', compact('memberTypes', 'places'));
    }

    public function getMembers()
    {
        $members = DB::table('members')
            ->join('companies', 'members.company_id', '=', 'companies.id')
            ->join('member_types', 'members.member_type_id', '=', 'member_types.id')
            ->join('places', 'members.place_id', '=', 'places.id')
            ->select('members.*', 'companies.company_name', 'member_types.member_type', 'places.place_name')
            ->orderBy('members.id', 'desc')
            ->get();
        $countMembers = $members->count();

        return response()->json([
            'members' => $members,
            'countMembers' => $countMembers
        ]);
    }

    public function insertMember(Request $request)
    {
        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'member_code' => 'required|unique:members,member_code',
                'phone' => 'required|numeric',
                'company_id' => 'required',
                'id_card' => 'required|numeric|digits:13',
                'place_id' => 'required',
                'member_type_id' => 'required',
                'license_driver' => 'required',
                'license_plate' => 'required',
                'issue_date' => 'required',
                'expiry_date' => 'required',
            ],
            [
                'first_name.required' => 'กรุณากรอกชื่อจริงสมาชิก',
                'last_name.required' => 'กรุณากรอกนามสกุลสมาชิก',
                'member_code.required' => 'กรุณากรอกรหัสสมาชิก',
                'member_code.unique' => 'รหัสสมาชิกนี้มีอยู่ในระบบแล้ว',
                'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'phone.numeric' => 'กรุณากรอกเป็นตัวเลขเท่านั้น',
                'company_id.required' => 'กรุณาเลือกบริษัท',
                'id_card.required' => 'กรุณากรอกเลขบัตรประชาชน',
                'id_card.numeric' => 'กรุณากรอกเลขบัตรประชาชนเป็นตัวเลขเท่านั้น',
                'id_card.digits' => 'กรุณากรอกเลขบัตรประชาชน 13 หลักเท่านั้น',
                'place_id.required' => 'กรุณาเลือกสถานที่',
                'member_type_id.required' => 'กรุณาเลือกประเภทสมาชิก',
                'license_driver.required' => 'กรุณากรอกเลขใบขับขี่รถยนต์',
                'license_plate.required' => 'กรุณากรอกป้ายทะเบียนรถยนต์',
                'issue_date.required' => 'กรุณาเลือกวันที่สมัครสมาชิก',
                'expiry_date.required' => 'กรุณาเลือกวันที่หมดอายุสมาชิก',
            ]
        );

        $data = $request->all();
        $data['member_status'] = 'active';

        try {
            Member::create($data);

            $response = [
                'status' => 'success',
                'message' => 'สร้างสมาชิกเรียบร้อยแล้ว'
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


    public function getMemberById($id)
    {
        try {
            $member = DB::table('members as m')
                ->leftJoin('member_types as mt', 'm.member_type_id', '=', 'mt.id')
                ->leftJoin('companies as c', 'm.company_id', '=', 'c.id')
                ->leftJoin('places as p', 'm.place_id', '=', 'p.id')
                ->leftJoin('company_setup as cs', 'm.company_id', '=', 'cs.company_id')
                ->leftJoin('stamps as s', 'cs.stamp_id', '=', 's.id')
                ->select('m.*', 'mt.member_type', 'c.company_name', 'p.place_name', DB::raw('GROUP_CONCAT(s.stamp_code) AS stamp_codes'))
                ->where('m.id', $id)
                ->groupBy('m.id')
                ->first();

            $response = [
                'status' => 'success',
                'message' => 'ดึงข้อมูลสมาชิกสำเร็จ',
                'member' => $member
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลสมาชิกนี้ในระบบ',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 404);
        }

        return response()->json($response);
    }

    public function updateMember(Request $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลสมาชิกนี้ในระบบ'
            ];
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'member_code' => 'required|' . Rule::unique('members', 'member_code')->ignore($member->id),
                'phone' => 'required|numeric',
                'company_id' => 'required',
                'id_card' => 'required|numeric|digits:13',
                'place_id' => 'required',
                'member_type_id' => 'required',
                'license_driver' => 'required',
                'license_plate' => 'required',
                'issue_date' => 'required',
                'expiry_date' => 'required',
            ],
            [
                'first_name.required' => 'กรุณากรอกชื่อจริงสมาชิก',
                'last_name.required' => 'กรุณากรอกนามสกุลสมาชิก',
                'member_code.required' => 'กรุณากรอกรหัสสมาชิก',
                'member_code.unique' => 'รหัสสมาชิกนี้มีอยู่ในระบบแล้ว',
                'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'phone.numeric' => 'กรุณากรอกเป็นตัวเลขเท่านั้น',
                'phone.digits' => 'กรุณากรอกเบอร์โทรศัพท์ 10 หลักเท่านั้น',
                'company_id.required' => 'กรุณาเลือกบริษัท',
                'id_card.required' => 'กรุณากรอกเลขบัตรประชาชน',
                'id_card.numeric' => 'กรุณากรอกเลขบัตรประชาชนเป็นตัวเลขเท่านั้น',
                'id_card.digits' => 'กรุณากรอกเลขบัตรประชาชน 13 หลักเท่านั้น',
                'place_id.required' => 'กรุณาเลือกสถานที่',
                'member_type_id.required' => 'กรุณาเลือกประเภทสมาชิก',
                'license_driver.required' => 'กรุณากรอกเลขใบขับขี่รถยนต์',
                'license_plate.required' => 'กรุณากรอกป้ายทะเบียนรถยนต์',
                'issue_date.required' => 'กรุณาเลือกวันที่สมัครสมาชิก',
                'expiry_date.required' => 'กรุณาเลือกวันที่หมดอายุสมาชิก',
            ]
        );

        $data = $request->all();

        if (Carbon::now()->lessThan(Carbon::parse($data['expiry_date']))) {
            $data['member_status'] = 'active';
        }
        else {
            $data['member_status'] = 'inactive';
        }

        try {
            $member->update($data);

            $response = [
                'status' => 'success',
                'message' => 'อัพเดทข้อมูลสมาชิกเรียบร้อยแล้ว',
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

    public function deleteMember($id)
    {
        $member = Member::find($id);

        if (!$member) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบสมาชิกนี้ในระบบ'
            ];
            return response()->json($response, 404);
        }


        try {
            $member->delete();

            $response = [
                'status' => 'success',
                'message' => 'สมาชิกถูกลบเรียบร้อยแล้ว',
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

    //API
    public function getMembersAPI()
    {
        $members = Member::all();
        return MemberResource::collection($members);
    }


    public function SaveData(Request $request)
    {

        try {
            $member = Member::create($request->all());

            return response()->json(['message' => 'Success', 'data' => $member], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }



    //API
    public function verifyMemberCode($memberCode)
    {
        $member = DB::table('members as m')
            ->leftJoin('member_types as mt', 'm.member_type_id', '=', 'mt.id')
            ->leftJoin('companies as c', 'm.company_id', '=', 'c.id')
            ->leftJoin('company_setup as cs', 'm.company_id', '=', 'cs.company_id')
            ->leftJoin('stamps as s', 'cs.stamp_id', '=', 's.id')
            ->select(
                'm.member_code',
                'm.first_name',
                'm.last_name',
                'm.phone',
                'm.id_card',
                'm.license_driver',
                'm.license_plate',
                'm.issue_date',
                'm.expiry_date',
                'm.member_status',
                'mt.member_type',
                'c.company_name'
            )
            ->where('m.member_code', $memberCode)
            ->groupBy('m.id')
            ->first();

        if ($member) {
            return response()->json(['status' => 'success', 'data' => $member]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Member not found'], 404);
        }
    }


    public function exportMemberList(Request $request) 
    {
        $carbonDate = Carbon::now();
        $carbonDateThai = $carbonDate->addYears(543);
        $dateThai = $carbonDateThai->locale('th')->isoFormat('D MMMM GGGG');
      
        return Excel::download(new ExportMemberList, 'รายชื่อข้อมูลสมาชิกวันที่_' . $dateThai . '.xlsx');
      
    
    }


    public function toggleMemberStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:members,id'
        ]);

        $member = Member::find($request->id);

        // Toggle the member's status
        $newStatus = $member->member_status === 'active' ? 'inactive' : 'active';
        $member->member_status = $newStatus;

        try {
            $member->save();

            $response = [
                'status' => 'success',
                'message' => 'อัพเดทสถานะสมาชิกเรียบร้อยแล้ว',
                'new_status' => $newStatus
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
}
