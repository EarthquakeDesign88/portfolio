@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

@php
    $number = !empty($_GET['page']) ? $_GET['page'] : 1;
    $number = ((10 * $number) - 10) + 1;
@endphp

<div class="card my-3">
    <h5 class="card-header">ตารางงานซ่อม/พบปัญหา</h5>
    <div class="card-body">
        @if(empty($jobs))
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">รายการปัญหา</th>
                        <th class="text-center">ผู้ตรวจ</th>
                        <th class="text-center">สถานที่</th>
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ช่วงเวลา</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    @php
                        $time = explode(" ", $job->inspection_completed_at)
                    @endphp
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$number++}}</span></td>
                        <td class="text-center">{{$job->issue_description}}</td>
                        <td class="text-center">{{$job->first_name}} {{$job->last_name}}</td>
                        <td class="text-center">{{$job->zone_description}}_{{$job->location_description}}</td>
                        <td class="text-center">{{$time[0]}}</td>
                        <td class="text-center">{{$time[1]}}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-label-warning me-1">กำลังดำเนินงาน</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <a href="" class="btn btn-outline-info btn-sm">รายละเอียด</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        <div class="row pt-4">
            {{ $jobs->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
@endsection