@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างกะการทำงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('workshift.create') }}" method="POST" id="workshift-form">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="workshift_desc" name="workshift_desc" placeholder="กรุณากรอกกะการทำงาน" aria-describedby="defaultFormControlHelp" />
                                <label for="workshift_desc">กะการทำงาน</label>
                                @error('workshift_desc')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                            @endif
                        </div>

                        <div class="col-2">
                            <div class="form-floating form-floating-outline">
                                <input type="time" class="form-control" id="shift_time_slot" name="shift_time_slot" placeholder="กรุณากรอกช่วงเวลา" aria-describedby="defaultFormControlHelp" />
                                <label for="shift_time_slot">ช่วงเวลาเริ่ม</label>
                                @error('shift_time_slot')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-floating form-floating-outline">
                                <input type="time" class="form-control" id="_shift_time_slot" name="_shift_time_slot" placeholder="กรุณากรอกช่วงเวลา" aria-describedby="defaultFormControlHelp" />
                                <label for="_shift_time_slot">ช่วงเวลาสิ้นสุด</label>
                                @error('_shift_time_slot')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_workshift" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(session('success_updated'))
<div class="alert alert-success mt-3">
    {{ session('success_updated') }}
</div>
@endif
<div class="card my-3">
    <h5 class="card-header">กะการทำงาน</h5>
    <div class="card-body">
        @if($workshift->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">กะการทำงาน</th>
                        <th class="text-center">ช่วงเวลา</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workshift as $key=>$work)
                    @php
                    $active = 'ใช้งาน';
                    $active_color = 'bg-label-success';
                    $check = '1';
                    if($work->work_shift_status == '0'){
                    $active = 'ปิดการใช้งาน';
                    $active_color = 'bg-label-danger';
                    $check = '0';
                    }
                    @endphp
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$key+1}}</span></td>
                        <td class="text-center">{{$work->work_shift_description}}</td>
                        <td class="text-center">{{$work->shift_time_slot}}</td>
                        <td width="10%" class="text-center">
                            <a id="status_{{$work->work_shift_id}}" class="btn badge rounded-pill {{$active_color}} me-1 px-3 py-2 status" data-type="{{$check}}">{{$active}}</a>
                        </td>
                        <td class="text-center" width="15%">
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <a href="{{ route('workshift.get', $work->work_shift_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                                <div id="{{$work->work_shift_id}}" class="btn btn-outline-danger btn-sm del_zone">ลบ</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $workshift->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#workshift-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_workshift');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('workshift.create') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('workshift') }}";
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        });
                    }
                    $submitButton.prop('disabled', false);
                },
            });
        });

        function changeStatus(id, status) {
            let element = $("#" + id);
            if (status == "1") {
                element.text("ใช้งาน")
                element.removeClass("bg-label-danger")
                element.addClass("bg-label-success")
                element.attr("data-type", status)
            } else {
                element.text("ปิดการใช้งาน")
                element.removeClass("bg-label-success")
                element.addClass("bg-label-danger")
                element.attr("data-type", status)
            }
        }

        $(document).on('click', '.status', function() {
            var $submitButton = $(this);
            $submitButton.prop('disabled', true);

            let id = $(this).attr("id");
            let status = $(this).attr("data-type");

            $.ajax({
                url: "{{ route('workshift.status') }}",
                type: 'PUT',
                data: {
                    "id": id,
                    "status": status
                },
                success: function(response) {
                    if (response.status == "success") {
                        changeStatus(id, response['workshift-status'])
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        });
                    }
                    $submitButton.prop('disabled', false);
                },
            });
        });

        $(document).on('click', '.del_zone', function() {

            Swal.fire({
                title: "คุณแน่ใจใช่หรือไม่?",
                text: "กะการทำงานจะถูกลบและไม่สามารถกู้คืนได้",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก"

            }).then((result) => {
                if (result.isConfirmed) {
                    var $submitButton = $(this);
                    $submitButton.prop('disabled', true);

                    let id = $(this).attr("id")

                    $.ajax({
                        url: "{{ route('workshift.del') }}",
                        type: 'PUT',
                        data: {
                            "id": id
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                Swal.fire({
                                    text: response.message,
                                    icon: response.status
                                }).then(() => {
                                    window.location = "{{ route('workshift') }}"
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: response.status
                                });
                            }
                            $submitButton.prop('disabled', false);
                        },
                    });
                }
            });

        });
    });
</script>