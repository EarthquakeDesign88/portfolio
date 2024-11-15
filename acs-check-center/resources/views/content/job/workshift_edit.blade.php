@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขกะการทำงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('workshift.update', $workshift->work_shift_id) }}" method="POST" id="workshift-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="workshift_desc" name="workshift_desc" value="{{$workshift->work_shift_description}}" placeholder="กรุณากรอกกะการทำงาน" aria-describedby="defaultFormControlHelp" />
                                <label for="workshift_desc" class="form-label">กะการทำงาน</label>
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
                                <input type="time" class="form-control" id="shift_time_slot" name="shift_time_slot" value="{{ $startTime }}">
                                <label for="shift_time_slot" class="form-label">ช่วงเวลาเริ่ม</label>
                                @error('shift_time_slot')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-floating form-floating-outline">
                                <input type="time" class="form-control" id="_shift_time_slot" name="_shift_time_slot" value="{{ $endTime }}" />
                                <label for="_shift_time_slot" class="form-label">ช่วงเวลาสิ้นสุด</label>
                                @error('_shift_time_slot')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_workshift" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#workshift-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_workshift');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('workshift.update', $workshift->work_shift_id) }}",
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
    });
</script>