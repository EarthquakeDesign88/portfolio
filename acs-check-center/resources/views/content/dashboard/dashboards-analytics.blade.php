@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="row gy-4">
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">ตารางงานวันนี้</h4>
                <p class="pb-0">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <h1 class="text-primary mb-3">{{count($jobSchedules)}}</h1>
                <a href="{{ route('jobschedules') }}" class="btn btn-sm btn-primary">ดูตารางงาน</a>
            </div>
            <img src="{{asset('assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background">
            <img src="{{asset('assets/file-list-line.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 mb-4 pb-2" width="100" alt="view job">
        </div>
    </div>

    @php
        $point_check = 0;
        $problem = 0;
        $not_check_point = 0;
        $check_percent = 0;
        foreach($jobSchedules as $job){
            if($job->job_schedule_status_id != 3){
                $point_check += 1;

                if($job->job_schedule_status_id == 2) {
                    $problem += 1;
                }
            }elseif($job->job_schedule_status_id == 3){
                $not_check_point += 1;
        }

        $check_percent = number_format( ($point_check / count($jobSchedules)) * 100, 2);
    }
    @endphp

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">สรุปข้อมูลจุดตรวจทั้งหมด</h5>
                    <div>
                        <!-- <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical mdi-24px"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            <a class="dropdown-item" href="javascript:void(0);">Update</a>
                        </div> -->
                    </div>
                </div>
                <!-- <p class="mt-3"><span class="fw-medium">Check point {{$check_percent}}% </span> this day</p> -->
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-info rounded shadow">
                                    <i class="mdi mdi-trending-up mdi-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">จุดตรวจทั้งหมด</div>
                                <h4 class="mb-0">{{count($jobSchedules)}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-success rounded shadow">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                </div>
                            </div>  
                            <div class="ms-3">
                                <div class="small mb-1">ตรวจไปแล้ว</div>
                                <h4 class="mb-0">{{$point_check}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-warning rounded shadow">
                                    <i class="mdi mdi-cellphone-link mdi-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">ยังไม่ได้ตรวจ</div>
                                <h4 class="mb-0">{{$not_check_point}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <div class="avatar-initial bg-danger rounded shadow">
                                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small mb-1">พบปัญหา</div>
                                <h4 class="mb-0">{{$problem}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection