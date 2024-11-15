@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างหัวข้อปัญหา</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('issueTopic.create') }}" method="POST" id="createIssueTopicForm">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="issue_description" name="issue_description" placeholder="กรุณากรอกหัวข้อปัญหา" aria-describedby="defaultFormControlHelp" />
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
                                        <option value="{{$user->user_id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                                <label for="Supervisor">ผู้รับผิดชอบ</label>
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
    <div class="card-body">
        @if($issueTopic->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">หัวข้อปัญหา</th>
                        <th class="text-center">ผู้รับผิดชอบ</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issueTopic as $key => $่issue)
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$key+1}}</span></td>
                        <td class="text-center">{{$่issue->issue_description}}</td>
                        <td class="text-center">{{$่issue->first_name}} {{$่issue->last_name}}</td>
                        <td width="10%" class="text-center"><span class="badge rounded-pill bg-label-success me-1">ใช้งาน</span></td>
                        <td class="text-center" width="15%">
                            <div class="btn-group" role="group" aria-label="Action Buttons">
                                <a href="{{ route('issueTopic.get', $่issue->issue_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $issueTopic->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#createIssueTopicForm').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_point');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('issueTopic.create') }}",
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