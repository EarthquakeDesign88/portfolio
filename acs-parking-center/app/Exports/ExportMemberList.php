<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ExportMemberList implements FromView
{
    public function view(): View
    {
        $members = DB::table('members')
            ->join('companies', 'members.company_id', '=', 'companies.id')
            ->join('member_types', 'members.member_type_id', '=', 'member_types.id')
            ->join('places', 'members.place_id', '=', 'places.id')
            ->select('members.*', 'companies.company_name', 'member_types.member_type', 'places.place_name')
            ->orderBy('members.id', 'desc')
            ->get();

        return view('exports.excel.member-list-excel', [
            'members' => $members
        ]);
    }
}
