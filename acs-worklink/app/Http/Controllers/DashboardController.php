<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;
use App\Models\JobQualification;
use App\Models\FormCreate;

class DashboardController extends Controller
{
    public function index()
    {
        $countDepartments = Department::count();
        $countPositions = Position::count();
        $countJobQualifications = JobQualification::count();
        $countReadEmails = FormCreate::where('mail_status', '=', '0')->count();

        return view('dashboard', compact('countDepartments', 'countPositions', 'countJobQualifications', 'countReadEmails'));
        
    }
}
