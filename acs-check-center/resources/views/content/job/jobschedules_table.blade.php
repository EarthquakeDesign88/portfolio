@php
$page = !empty($_GET['page']) ? $_GET['page'] : 1;
$start = ((10 * $page) - 10) + 1;
@endphp
<div class="card-body">
    @if($jobSchedules->isEmpty())
    <p>ไม่พบข้อมูล</p>
    @else
    <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ลำดับ</th>
                    <th class="text-center">กะการทำงาน</th>
                    <th class="text-center">จุดตรวจ</th>
                    <th class="text-center">พื้นที่</th>
                    <th class="text-center">สถานะงาน</th>
                    <th class="text-center">ผู้ตรวจงาน</th>
                    <th class="text-center">เวลาที่ตรวจสอบ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobSchedules as $key => $job)
                <tr>
                    <td class="text-center" width="5%"><span class="fw-medium">{{ $start++ }}</span></td>
                    <td class="text-center">{{$job->work_shift_description}} / {{$job->shift_time_slot}}</td>
                    <td class="text-center">{{$job->zone_description}}_{{$job->location_description}}</td>
                    <td class="text-center">{{$job->zone_description}}</td>
                    <td width="20%" class="text-center">
                        @if($job->job_status_id == '1')
                        <span class="badge rounded-pill bg-label-success me-1 p-2">{{ $job->job_status_description }}</span>
                        @elseif($job->job_status_id == '2')
                        <span class="badge rounded-pill bg-label-danger me-1 p-2">{{ $job->job_status_description }}</span><a href="{{ route('jobschedule_image', $job->job_schedule_id) }}"> ดูข้อมูล</a>
                        @elseif($job->job_status_id == '3')
                        <span class="badge rounded-pill bg-label-warning me-1 p-2">{{ $job->job_status_description }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{$job->first_name}} {{$job->last_name}}</td>
                    <td class="text-center">{{$job->inspection_completed_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row pt-4">
        {{ $jobSchedules->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endif