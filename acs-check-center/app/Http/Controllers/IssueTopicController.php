<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IssueTopic;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IssueTopicController extends Controller
{
    public function issueTopics()
    {
        $issueTopic = DB::table('issue_topics')
            ->join('users', 'issue_topics.supervisor_id', '=', 'users.user_id')
            ->paginate(10);

        $users = USer::all();

        return view('content.issue.issue_topic', compact('issueTopic', 'users'));
    }

    public function issueTopic_get(Request $request, $id)
    {
        $issueTopic = IssueTopic::findOrFail($id);
        $users = USer::all();

        return view('content.issue.issue_topic_edit', compact('issueTopic', 'users'));
    }

    public function issueTopic_update(Request $request, $id)
    {

        if (empty($request->input('issue_description'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกหัวข้อปัญหา']);
        }

        if (empty($request->input('supervisor_id'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกผู้รับผิดชอบ']);
        }

        try {
            $count = DB::table('issue_topics')
                ->where('issue_description', '=', $request->input('issue_description'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'หัวข้อปัญหาซ้ำ']);
            }

            $issueTopic = IssueTopic::findOrFail($id);
            $issueTopic->issue_description = $request->input('issue_description');
            $issueTopic->supervisor_id = $request->input('supervisor_id');

            $issueTopic->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทหัวข้อปัญหาสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function issueTopic_create(Request $request)
    {

        if (empty($request->input('issue_description'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกหัวข้อปัญหา']);
        }

        if (empty($request->input('supervisor_id'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกผู้รับผิดชอบ']);
        }

        try {
            $count = DB::table('issue_topics')
                ->where('issue_description', '=', $request->input('issue_description'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'หัวข้อปัญหาซ้ำ']);
            }

            $issueTopic = new IssueTOpic;
            $issueTopic->issue_description = $request->input('issue_description');
            $issueTopic->supervisor_id = $request->input('supervisor_id');

            $issueTopic->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างหัวข้อปัญหาสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }
}
