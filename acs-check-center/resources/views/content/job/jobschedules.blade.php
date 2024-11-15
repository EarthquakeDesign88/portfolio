@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<style>
    .status-indicator {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .status-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 8px;
        border-radius: 3px;
        vertical-align: middle;
    }
</style>

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">ตารางงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route(('jobschedules')) }}" method="GET">
                    <div class="row">
                        <div class="col-3">
                            <label for="defaultFormControlInput" class="form-label">กะการทำงาน</label>
                            <div class="form-floating-outline">
                                <select class="form-select" id="work_shift" name="work_shift" aria-label="Default select example">
                                    <option value="">ทุกช่วงเวลา</option>
                                    @foreach($workshift as $work)
                                    <option value="{{$work->work_shift_id}}">{{$work->work_shift_description}} {{$work->shift_time_slot}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="defaultFormControlInput" class="form-label">พื้นที่</label>
                            <div class="form-floating-outline">
                                <select class="form-select" id="zone" name="zone" aria-label="Default select example">
                                    <option value="">เลือกพื้นที่</option>
                                    @foreach($zones as $zone)
                                    <option value="{{$zone->zone_id}}">{{$zone->zone_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="defaultFormControlInput" class="form-label">สถานะงาน</label>
                            <div class="form-floating-outline">
                                <select class="form-select" id="job_status" name="job_status" aria-label="Default select example">
                                    <option value="">เลือกสถานะงาน</option>
                                    @foreach($jobStatus as $status)
                                    <option value="{{$status->job_status_id}}">{{$status->job_status_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="defaultFormControlInput" class="form-label">วันที่</label>
                            <input type="date" class="form-control" name="date" id="date" aria-describedby="defaultFormControlHelp" />
                        </div>
                    </div>
                    <div class="col-6">
                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">ค้นหา</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card my-3">
    <div class="col-md">
        <div class="card">
            <div class="row">
                <div class="col-6">
                    <h5 class="card-header">ตารางงานวันที่ {{$date}}
                        <span class="ms-3">
                            <a href="#" id="toggleCardView" class="btn btn-sm btn-outline-secondary">
                                <i class="mdi mdi-view-module"></i>
                            </a>
                            <a href="#" id="toggleTableView" class="btn btn-sm btn-outline-secondary">
                                <i class="mdi mdi-table"></i>
                            </a>
                        </span>
                    </h5>
                </div>


                <div class="col-6 float-end">
                    <div class="col-6 text-end">
                        @foreach($jobStatus as $key => $status)
                        @php
                        $statusColor = match($status->job_status_id) {
                        1 => 'bg-success',
                        2 => 'bg-danger',
                        default => 'bg-warning',
                        };
                        @endphp
                        <div class="status-indicator mt-2">
                            <span class="status-box {{ $statusColor}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$status->job_status_description}}"></span> {{$status->job_status_description}}
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="card-body" style="padding-bottom: 8px;" id="cardView" style="display: block;">
                <form action="" method="POST">
                    @csrf
                    @if($_zones->isEmpty())
                    <p>ไม่พบข้อมูล</p>
                    @else
                    <div class="row">
                        @foreach($_workshift as $work)
                        @php
                        $count_workshift = $_jobSchedules->where('work_shift_id', $work->work_shift_id)->count();
                        @endphp
                        <div class="row">
                            <h5 class="card-header text-center bg-dark mb-3 text-light">กะการทำงาน : {{$work->work_shift_description}} / {{$work->shift_time_slot}} ({{$count_workshift}})</h5>
                            @foreach($_zones as $zone)
                            @php
                            $hasJobSchedules = $_jobSchedules->where('zone_id', $zone->zone_id)->where('work_shift_id', $work->work_shift_id)->count();
                            @endphp

                            @if($hasJobSchedules > 0)

                            <div class="col-4">
                                <div class="">Zone : {{$zone->zone_description}} ({{$hasJobSchedules}})</div>
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
                                    <a href="{{ route('jobschedule_image', $jobSchedule->job_schedule_id) }}"
                                        class="text-center {{$statusColor}}"
                                        style="color: white; width: 5px; height: 15px; margin: 5px"
                                        id="{{$jobSchedule->job_schedule_id}}"
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

<div class="card my-3" id="tableView" style="display: none;">
    @include('content.job.jobschedules_table', ['jobSchedules' => $jobSchedules])
</div>

<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('65f1b2a0980c396a266b', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('acsCheck');
    channel.bind('saveInspectionResult', function(data) {
        // Use the correct variable names to update the UI
        updateUIWithNewData(data.job_schedule_id, data.job_schedule_status);
    });
    

    function updateUIWithNewData(jobScheduleId, jobScheduleStatusId) {
        var element = $(`#${jobScheduleId}`);
        if (element.length === 0) {
            console.error(`Element with ID ${jobScheduleId} not found.`);
            return;
        }

        element.removeClass('bg-success bg-danger bg-warning');

        jobScheduleStatusId = parseInt(jobScheduleStatusId);

        switch (jobScheduleStatusId) {
            case 1: 
                element.addClass('bg-success');
                break;
            case 2:
                element.addClass('bg-danger');
                break;
            default:
                element.addClass('bg-warning');
                break;
        }
    }
</script>


<script type="text/javascript">
    document.getElementById('toggleCardView').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('cardView').style.display = 'block';
        document.getElementById('tableView').style.display = 'none';
    });

    document.getElementById('toggleTableView').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('cardView').style.display = 'none';
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var queryString = window.location.search;
        var params = new URLSearchParams(queryString);
        var page = $(this).attr('href').split('page=')[1];

        var token = $('meta[name="csrf-token"]').attr('content'); 
        var workshift = params.get('work_shift') ? params.get('work_shift') : '';
        var zone = params.get('zone') ? params.get('zone') : '';
        var jobstatus = params.get('job_status') ? params.get('job_status') : '';
        var date = params.get('date') ? params.get('date') : '';

        var search = `&work_shift=${workshift}&zone=${zone}&jobstatus=${jobstatus}&date=${date}`

        // console.log(token)
        $.ajax({
            url: '?page=' + page + search,
            type: "GET",
            data: {
                _token: token, 
            },
            dataType: "json",
            success: function(data) {
                $('#tableView').html(data.html);
                window.history.pushState("", "", '?page=' + page + search);
            },
            error: function() {
                alert('ไม่สามารถโหลดข้อมูลได้');
            }
        });
    });
</script>
@endsection