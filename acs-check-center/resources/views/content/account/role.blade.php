@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">สร้างบทบาท</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('role.create') }}" method="POST" id="role-form">
                    @csrf
                    <div class="col-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="role_name" name="role_name" autofocus placeholder="กรุณากรอกบทบาท" aria-describedby="defaultFormControlHelp" />
                            <label for="role_name">บทบาท</label>
                            @error('role_name')
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
                        <button type="submit" id="save_role" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">สร้าง</button>
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
    <h5 class="card-header">บทบาท</h5>
    <div class="card-body">
        @if($roles->isEmpty())
        <p>ไม่พบข้อมูล</p>
        @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">บทบาท</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td class="text-center" width="5%"><span class="fw-medium">{{$role->role_id}}</span></td>
                        <td class="text-center">{{$role->role_name}}</td>
                        <td width="20%" class="text-center"><span class="badge rounded-pill bg-label-success me-1">ใช้งาน</span></td>
                        <td class="text-center" width="30%">
                            @if($role->role_name != 'Admin')
                            <a href="{{ route('role.get', $role->role_id) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row pt-4">
                {{ $roles->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#role-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_role');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('role.create') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('roles') }}";
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