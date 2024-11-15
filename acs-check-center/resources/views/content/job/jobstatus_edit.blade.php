@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<!-- Bordered Table -->
<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">Edit Job Status Description</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('jobstatus.update', $job->job_status_id) }}" method="POST" id="jobstatus-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="col-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" name="job_desc" value="{{$job->job_status_description}}" placeholder="Please enter job description" aria-describedby="defaultFormControlHelp" />
                        <label for="job_desc" class="form-label">Job Description</label>
                        @error('job_desc')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_jobstatus" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">Update Job</button>
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
        $('#jobstatus-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_jobstatus');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('jobstatus.update', $job->job_status_id) }}",
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