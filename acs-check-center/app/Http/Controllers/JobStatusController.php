<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\JobStatus;

class JobStatusController extends Controller
{
    public function fetchJobStatuses()
    {
        $jobStatuses = JobStatus::select(
            'job_statuses.job_status_id',
            'job_statuses.job_status_description'
        )
        ->get();

        return response()->json($jobStatuses);
    }
}
