@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

<div class="card">
    <h4 class="card-header">แก้ไขบัญชีผู้ใช้</h4>
    <!-- Account -->
    <div class="card-body pt-2">
        <form action=" {{ route('account.update', $account->user_id) }}" method="POST" id="account-edit-form">
            @csrf
            @method('PUT')
            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="firstName" name="firstName" value="{{ $account->first_name }}" autofocus />
                        <label for="firstName">ชื่อจริง</label>
                        @error('firstName')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" name="lastName" id="lastName" value="{{ $account->last_name }}" />
                        <label for="lastName">นามสกุล</label>
                        @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="email" name="email" value="{{ $account->email }}" placeholder="john.doe@example.com" />
                        <label for="email">อีเมล</label>
                        @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select id="role" name="role" class="select2 form-select">
                            @foreach($roles as $role)
                            <option value="{{$role->role_id}}" {{$role->role_id == $account->role_id ? 'selected' : '' }}>{{$role->role_name}}</option>
                            @endforeach
                        </select>
                        <label for="role">บทบาท</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="username" name="username" value="{{ $account->user_name }}" readonly />
                        <label for="username">ชื่อบัญชีผู้ใช้</label>
                        @error('username')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="new_password" name="new_password" id="password" />
                        <label for="new_password">รหัสผ่านใหม่</label>
                        @error('new_password')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
            @endif
            <div class="mt-4">
                <button type="submit" id="save_edit_account" class="btn btn-primary me-2">อัพเดทบัญชี</button>
            </div>
        </form>
    </div>
    <!-- /Account -->
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#account-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_account');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('account.update', $account->user_id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('accounts') }}";
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