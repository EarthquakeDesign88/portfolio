<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkShift;

class DashboardController extends Controller
{
    public function index()
    {
        $workshift = WorkShift::all();
        
        return view('content.tables.tables-basic', compact("workshift"));
    }
}
