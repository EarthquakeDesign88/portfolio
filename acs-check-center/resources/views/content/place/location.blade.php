@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างจุดตรวจ</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('location.create') }}" method="POST" id="location-form">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="location_description" name="location_description" placeholder="กรุณากรอกจุดตรวจ" aria-describedby="defaultFormControlHelp" />
                                <label for="location_description">จุดตรวจ</label>
                                @error('location_description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                            @endif
                        </div>

                        <div class="col-3">

                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="location_zone" name="location_zone" aria-label="Default select example">
                                    @foreach($zones as $zone)
                                    <option value="{{$zone->zone_id}}">{{$zone->zone_description}}</option>
                                    @endforeach
                                </select>
                                <label for="location_zone">พื้นที่</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on" id="save_point">ยืนยัน</button>
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

@if(session('del'))
<div class="alert alert-success mt-3">
    {{ session('del') }}
</div>
@endif

<div class="card my-3">
    <h5 class="card-header">ค้นหา จุดตรวจ</h5>
    <form action="{{ route('locations') }}" method="GET">
        @csrf
        <div class="row mx-2">
            <div class="col-4">
                <label for="defaultFormControlInput" class="form-label">จุดตรวจ</label>
                <div class="form-floating-outline">
                    <select class="form-select" id="location_search" name="location_search" aria-label="Default select example">
                        <option value="">เลือกจุดตรวจ</option>
                        @foreach($locations_search as $location_search)
                        <option value="{{$location_search->location_id}}">{{$location_search->zone_description}}_{{$location_search->location_description}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <label for="defaultFormControlInput" class="form-label">พื้นที่</label>
                <div class="form-floating-outline">
                    <select class="form-select" id="location_zone_search" name="location_zone_search" aria-label="Default select example">
                        <option value="">เลือกพื้นที่</option>
                        @foreach($zones as $zone)
                        <option value="{{$zone->zone_id}}">{{$zone->zone_description}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @if(session('error'))
        {{ session('error') }}
        @endif
        <div class="row mx-2 mt-3">
            <div class="col-6">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </div>
        </div>
    </form>

    <form action="{{ route('report.location') }}" method="GET">
        <div class="row mx-2 mt-3">
            <div class="col-6"></div>
            <div class="col-6">
                <button class="btn btn-primary float-end" type="submit">สร้าง QR Code</button>
            </div>
        </div>
    </form>

    <div class="card-body">
        @if($locations->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">จุดตรวจ</th>
                        <th class="text-center">QR CODE</th>
                        <th class="text-center">พื้นที่</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $key => $location)
                    @php
                    $active = 'ใช้งาน';
                    $active_color = 'bg-label-success';
                    $check = '1';
                    if($location->location_status == '0'){
                    $active = 'ปิดการใช้งาน';
                    $active_color = 'bg-label-danger';
                    $check = '0';
                    }

                    @endphp
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$key+1}}</span></td>
                        <td class="text-center">{{$location->zone_description}}_{{$location->location_description}}</td>
                        <td class="text-center"><img src="{{ asset('storage/qr_codes/qr_' . $location->location_qr . '.svg') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="" /></td>
                        <td class="text-center">{{$location->zone_description}}</td>
                        <td width="10%" class="text-center">
                            <a id="status_{{$location->location_id}}" class="btn badge rounded-pill {{$active_color}} me-1 px-3 py-2 status" data-type="{{$check}}">{{$active}}</a>
                        </td>
                        <td class="text-center" width="15%">
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <a href="{{ route('location.get', $location->location_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                                <div id="{{$location->location_id}}" class="btn btn-outline-danger btn-sm del_location">ลบ</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $locations->links('vendor.pagination.bootstrap-4') }}
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

        $(document).on('click', '.del_location', function() {

            Swal.fire({
                title: "คุณแน่ใจใช่หรือไม่?",
                text: "สถานที่จะถูกลบและไม่สามารถกู้คืนได้",
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
                        url: "{{ route('location.del') }}",
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
                                    window.location = "{{ route('locations') }}"
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
                url: "{{ route('location.status') }}",
                type: 'PUT',
                data: {
                    "id": id,
                    "status": status
                },
                success: function(response) {
                    if (response.status == "success") {
                        changeStatus(id, response['location-status'])
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


        $('#location-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_point');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('location.create') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('locations') }}";
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
    });
</script>