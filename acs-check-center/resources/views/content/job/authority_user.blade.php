@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">จัดการสิทธิผู้ตรวจงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <div class="card-body" style="padding-bottom: 8px;" id="cardView" style="display: block;">
                    <form action="" method="POST">
                        @csrf
                        @if($_zones->isEmpty())
                        <p>ไม่พบข้อมูล</p>
                        @else
                        <div class="row">
                            @foreach($_workshift as $work)
                            <div class="row">
                                <h5 class="card-header text-center bg-dark mb-3 text-light">กะการทำงาน : {{$work->work_shift_description}} / {{$work->shift_time_slot}}</h5>
                                @foreach($_zones as $zone)
                                @php
                                $hasJobSchedules = $_jobSchedules->where('zone_id', $zone->zone_id)->where('work_shift_id', $work->work_shift_id)->count();
                                @endphp

                                @if($hasJobSchedules > 0)
                                <div class="col-4">
                                    <div class="">Zone : {{$zone->zone_description}}</div>
                                    <div class="row">
                                        @foreach($_jobSchedules as $jobSchedule)
                                        @php
                                        $statusColor = match($jobSchedule->job_schedule_status_id) {
                                        1 => 'bg-success',
                                        2 => 'bg-danger',
                                        default => 'bg-warning',
                                        };
                                        @endphp
                                        @if($jobSchedule->zone_id == $zone->zone_id && $jobSchedule->work_shift_id == $work->work_shift_id)
                                        <a href="{{ route('authority.get', $jobSchedule->user_authority_id) }}"
                                            class="text-center {{$statusColor}}"
                                            style="color: white; width: 5px; height: 15px; margin: 5px"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-original-title="{{$jobSchedule->zone_description}}_{{$jobSchedule->location_description}}">&nbsp;
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>

                                @endif


                                @endforeach

                            </div>
                            @endforeach
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection