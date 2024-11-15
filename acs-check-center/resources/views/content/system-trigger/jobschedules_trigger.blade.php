@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างตารางงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form id="workshift-form">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="job_schedule_date" name="job_schedule_date" placeholder="กรุณาเลือกวันที่" aria-describedby="defaultFormControlHelp" />
                                <label for="JobScheduleDate">เลือกวันที่</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="jobschedules-trigger" class="btn btn-primary waves-effect waves-light">สร้างตารางงาน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Set default date to today
        var today = new Date().toISOString().split('T')[0];
        var endCalendar = new Date();
        endCalendar.setDate(endCalendar.getDate() + 30);
        var endCalendarDate = endCalendar.toISOString().split('T')[0];

        $('#job_schedule_date').val(today);
        $('#job_schedule_date').attr('min', today);
        $('#job_schedule_date').attr('max', endCalendarDate);


        $('#workshift-form').submit(function(event) {
            event.preventDefault(); 

            var formData = {
                _token: $('input[name="_token"]').val(),
                job_schedule_date: $('#job_schedule_date').val(),
            };

            $('#jobschedules-trigger').prop('disabled', true);

            $.ajax({
                url: "{{ route('jobschedulesTrigger.run') }}", 
                type: "POST",
                data: formData,
                beforeSend: function() {
                    Swal.fire({
                        title: 'กำลังประมวลผล',
                        text: 'กรุณารอสักครู่...',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    $('#jobschedules-trigger').prop('disabled', false);
                    if(response.status === 'success') {
                        Swal.fire({
                            title: 'สำเร็จ!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                    else {
                        Swal.fire({
                            title: 'พบข้อผิดพลาด',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                },
                error: function(xhr) {
                    $('#jobschedules-trigger').prop('disabled', false);
                    var errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'ไม่สามารถสร้างตารางงานได้';
                    Swal.fire({
                        title: 'พบข้อผิดพลาด',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        });
    });
</script>
