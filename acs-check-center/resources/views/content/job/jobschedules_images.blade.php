@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">รายละเอียดการตรวจงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                    <div class="row">
                        <div class="col-6 my-2">
                            <label for="defaultFormControlInput" class="form-label">ชื่อผู้ตรวจงาน</label>
                            <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->first_name }}" readonly />
                        </div>
                        <div class="col-6 my-2">
                            <label for="defaultFormControlInput" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->last_name }}" readonly />
                        </div>
                        <div class="col-3 my-2">
                            <label for="defaultFormControlInput" class="form-label">กะการทำงาน</label>
                            <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->work_shift_description }} / {{ $jobSchedules->shift_time_slot }}" readonly />
                        </div>
                        <div class="col-3 my-2">
                            <label for="defaultFormControlInput" class="form-label">พื้นที่</label>
                            <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->zone_description }}" readonly />
                        </div>
                        <div class="col-3 my-2">
                            <label for="defaultFormControlInput" class="form-label">จุดตรวจ</label>
                            <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->zone_description }}_{{ $jobSchedules->location_description }}" readonly />
                        </div>
                        @php
                            $statusColor = match($jobSchedules->job_schedule_status_id){
                                1 => 'success',
                                2 => 'danger',
                                default => 'warning'
                            };

                        @endphp

                        @if($jobSchedules->job_schedule_status_id != 3)
                            <div class="col-3 my-2">
                                <label for="defaultFormControlInput" class="form-label">ตรวจสอบเวลา</label>
                                <input type="text" class="form-control" aria-describedby="defaultFormControlHelp" value="{{ $jobSchedules->inspection_completed_at }}" readonly />
                            </div>
                        @else 
                            <div class="col-3 my-2"></div>
                        @endif
                        <div class="col-3 my-2">
                            <label for="defaultFormControlInput" class="form-label">สถานะงาน </label>
                            <span class="badge rounded-pill bg-label-{{ $statusColor }} p-2">{{ $jobSchedules->job_status_description }}</span>
                        </div>
                       
                    </div>
            </div>
        </div>
    </div>
</div>
@if($jobImage->isNotEmpty())
    <div class="card my-3">
        <h5 class="card-header">รูปภาพปัญหา</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <div class="row">
                    @foreach($jobImage as $job)
                    <div class="col-4 pb-4">
                        <img src="{{ asset('storage/' . $job->image_path) }}" width="100%" height="250" />
                    </div>
                    @endforeach
                </div>
            </div>  
        </div>
    </div>
@endif
@endsection