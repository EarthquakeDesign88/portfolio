@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">กำหนดสิทธิผู้ตรวจงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="user_id" name="user_id" aria-label="Default select example">
                                    @foreach($accounts as $account)
                                    @php
                                    $check = "";
                                    if($firstId == $account->user_id){
                                    $check = "selected";
                                    }

                                    @endphp
                                    <option value="{{$account->user_id}}" {{$check}}>{{$account->first_name}} {{$account->last_name}}</option>
                                    @endforeach
                                </select>
                                <label for="user_id">เลือกผู้ตรวจงาน</label>
                                @error('user_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="location" name="location" aria-label="Default select example">
                                    @foreach($locations as $location)
                                    <option value="{{$location->location_id}}">{{$location->zone_description}}_{{$location->location_description}}</option>
                                    @endforeach
                                </select>
                                <label for="location">เลือกจุดตรวจ</label>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-6">
                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>

                    <div class="row mt-3" id="show_location">
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="save_authority" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
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
    <h5 class="card-header">สิทธิผู้ตรวจงาน</h5>
    <div class="card-body">
        @if($authorities->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">ชื่อผู้ตรวจงาน</th>
                        <th class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($authorities as $key => $authority)

                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$key + 1}}</span></td>
                        <td class="text-center">{{$authority->first_name}} {{$authority->last_name}}</td>
                        <td width="20%" class="text-center"><span class="badge rounded-pill bg-label-success me-1">ใช้งาน</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $authorities->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        var arr_ = {};
        fetchLocationAuthority()

        $('#user_id').on('change', function(event) {
            let id = $(this).val()

            $.ajax({
                url: "{{ route('authority') }}",
                type: 'GET',
                data: {
                    "id": id
                },
                beforeSend: function() {
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                },
                success: function(response) {
                    showLocation(response, id)
                },
            });
        });

        $('#save_authority').click(function(event) {

            let $submitButton = $('#save_authority');
            $submitButton.prop('disabled', true);

            let selected = $("#user_id").val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('authority.create') }}",
                type: 'POST',
                data: {
                    "id": arr_,
                    "user_id": selected,
                },
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        })
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

        function fetchLocationAuthority() {
            let selected = $("#user_id").val();

            $.ajax({
                type: "GET",
                url: "{{ route('authority') }}",
                success: function(data) {
                    showLocation(data, selected)
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        function showLocation(data, id = '') {
            let bg = 'bg-danger'
            html = ''

            for (let i = 0; i < data['zones'].length; i++) {
                html += `   <div class="col-4">
                            <div class="mt-3">Zone : ${data['zones'][i]['zone_description']}</div>
                            <div class="row">`
                for (let j = 0; j < data['locations'].length; j++) {
                    for (let k = 0; k < data['authority_query'].length; k++) {
                        let auturity = data['authority_query']

                        if (data['locations'][j]['location_id'] == auturity[k]['user_location_id'] && auturity[k]['user_id'] == id && auturity[k]['user_authority_status'] != '0') {
                            bg = 'bg-success';
                            break;
                        } else if (data['locations'][j]['location_id'] == auturity[k]['user_location_id'] && auturity[k]['user_id'] != id && auturity[k]['user_authority_status'] != '0') {
                            bg = 'bg-info';
                            break;
                        } else {
                            bg = 'bg-danger';
                        }
                    }

                    if (data['zones'][i]['zone_id'] == data['locations'][j]['location_zone_id']) {
                        html += `<a
                                class="text-center ${bg} user_location"
                                style="color: white; width: 5px; margin: 5px; padding: 10px"
                                id="${data['locations'][j]['location_id']}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                data-bs-original-title="${data['locations'][j]['zone_description']}_${data['locations'][j]['location_description']}">
                        </a>`
                    }
                }
                html += '</div></div>'
            }

            $('#show_location').html(html);
            $('[data-bs-toggle="tooltip"]').tooltip('dispose');
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        $(document).on('click', '.user_location', function() {
            if ($(this).hasClass("bg-info")) {
                Swal.fire({
                    text: 'ไม่สามารถเลือกสถานที่นี้ได้',
                    icon: 'info'
                });

                return
            }

            let bg = $(this).hasClass("bg-danger");
            let id = $(this).attr("id");
            if (bg) {
                $(this).removeClass('bg-danger').addClass('bg-success');
                arr_[id] = true;
            } else {
                $(this).removeClass('bg-success').addClass('bg-danger');
                arr_[id] = false;
            }
        });
    });
</script>