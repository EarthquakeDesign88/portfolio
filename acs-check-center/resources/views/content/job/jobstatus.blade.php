@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<!-- Bordered Table -->
<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างสถานะงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('jobstatus.create') }}" method="POST" id="jobstatus-form">
                    @csrf
                    <div class="col-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="job_desc" name="job_desc" placeholder="กรุณากรอกสถานะงาน" aria-describedby="defaultFormControlHelp" />
                            <label for="job_desc" class="form-label">สถานะงาน</label>
                            @error('job_desc')
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
                        <button type="submit" id="save_jobstatus" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">ยืนยัน</button>
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
    <h5 class="card-header">สถานะงาน</h5>
    <div class="card-body">
        @if($่job_status->isEmpty())
        <p>No users found.</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">สถานะงาน</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($่job_status as $job)
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$job->job_status_id}}</span></td>
                        <td class="text-center">{{$job->job_status_description}}</td>
                        <td class="text-center" width="30%">
                            <a href="{{ route('jobstatus.get', $job->job_status_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $่job_status->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#jobstatus-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_jobstatus');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('jobstatus.create') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('jobstatus') }}";
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