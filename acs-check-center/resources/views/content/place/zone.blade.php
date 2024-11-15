@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างพื้นที่</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('zone.create') }}" method="POST" id="zone-form">
                    @csrf
                    <div class="col-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="zone_desc" name="zone_desc" placeholder="โปรดกรอกพื้นที่" aria-describedby="defaultFormControlHelp" />
                            <label for="zone_desc">พื้นที่</label>
                            @error('zone_desc')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_zone" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">ยืนยัน</button>
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
    <h5 class="card-header">พื้นที่</h5>
    <div class="card-body">
        @if($zones->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">พื้นที่</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zones as $key=> $zone)
                    @php
                    $active = 'ใช้งาน';
                    $active_color = 'bg-label-success';
                    $check = '1';
                    if($zone->zone_status == '0'){
                    $active = 'ปิดการใช้งาน';
                    $active_color = 'bg-label-danger';
                    $check = '0';
                    }
                    @endphp
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$key + 1}}</span></td>
                        <td class="text-center">{{$zone->zone_description}}</td>
                        <td width="20%" class="text-center">
                            <a id="status_{{$zone->zone_id}}" class="btn badge rounded-pill {{$active_color}} me-1 px-3 py-2 status" data-type="{{$check}}">{{$active}}</a>
                        </td>
                        <td class="text-center" width="30%">
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <a href="{{ route('zone.get', $zone->zone_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                                <div id="{{$zone->zone_id}}" class="btn btn-outline-danger btn-sm del_zone">ลบ</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $zones->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#zone-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_zone');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('zone.create') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('zones') }}";
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
                url: "{{ route('zone.status') }}",
                type: 'PUT',
                data: {
                    "id": id,
                    "status": status
                },
                success: function(response) {
                    if (response.status == "success") {
                        changeStatus(id, response['zone-status'])
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
                text: "สถานที่ทั้งหมดที่อยู่ในพื้นที่จะถูกลบทั้งหมด",
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
                        url: "{{ route('zone.del') }}",
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
                                    window.location = "{{ route('zones') }}"
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