@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขหัวข้อปัญหา</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('issueTopic.update', $issueTopic->issue_id) }}" method="POST" id="editIssueTopicForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="issue_description" name="issue_description" value="{{ $issueTopic->issue_description }}" placeholder="กรุณากรอกหัวข้อปัญหา" aria-describedby="defaultFormControlHelp" />
                                <label for="issueDescription">หัวข้อปัญหา</label>
                                @error('issue_description')
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
                                <select class="form-select" id="supervisor_id" name="supervisor_id" aria-label="Default select example">
                                    @foreach($users as $user)
                                        <option value="{{$user->user_id}}" {{$user->user_id == $issueTopic->supervisor_id ? 'selected' : '' }}>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                                <label for="Supervisor">ผู้รับผิดชอบ</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_point" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
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
        $('#editIssueTopicForm').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_point');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('issueTopic.update', $issueTopic->issue_id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('issueTopics') }}";
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