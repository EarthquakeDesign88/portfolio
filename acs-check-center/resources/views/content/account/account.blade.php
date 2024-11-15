@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

<div class="card">
  <h4 class="card-header">สร้างบัญชีผู้ใช้</h4>
  <!-- Account -->
  <div class="card-body pt-2">
    <form action="{{ route('account.create') }}" method="POST" id="account-form">
      @csrf
      <div class="row gy-4">
        <div class="col-md-6">
          <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" id="firstName" name="firstName" autofocus placeholder="กรุณากรอกชื่อจริง" />
            <label for="firstName">ชื่อจริง</label>
            @error('firstName')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" name="lastName" id="lastName" placeholder="กรุณากรอกนามสกุล" />
            <label for="lastName">นามสกุล</label>
            @error('email')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" id="email" name="email" placeholder="กรุณากรอกอีเมล" />
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
              <option value="{{$role->role_id}}">{{$role->role_name}}</option>
              @endforeach
            </select>
            <label for="role">บทบาท</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" id="username" name="username" autofocus placeholder="กรุณากรอกชื่อบัญชีผู้ใช้" />
            <label for="username">ชื่อบัญชีผู้ใช้</label>
            @error('username')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating form-floating-outline">
            <input class="form-control" type="password" name="password" id="password" placeholder="กรุณากรอกรหัสผ่าน" />
            <label for="password">รหัสผ่าน</label>
            @error('password')
            <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success mt-3">
        {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger mt-3">
        {{ session('error') }}
      </div>
      @endif

      <div class="mt-4">
        <button type="submit" id="save_account" class="btn btn-primary me-2">สร้างบัญชี</button>
      </div>
    </form>
  </div>
  <!-- /Account -->
</div>
@if(session('success_updated'))
<div class="alert alert-success mt-3">
  {{ session('success_updated') }}
</div>
@endif
<div class="card my-3">
  <h5 class="card-header">บัญชี</h5>
  <div class="card-body">
    @if($accounts->isEmpty())
    <p>ไม่พบข้อมูล</p>
    @else
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center">ลำดับ</th>
            <th class="text-center">ชื่อบัญชี</th>
            <th class="text-center">อีเมล</th>
            <th class="text-center">บทบาท</th>
            <th class="text-center">สถานะ</th>
            <th class="text-center">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
          <tr>
            <td class="text-center" width="5%"><span class="fw-medium">{{$account->user_id}}</span></td>
            <td class="text-center">{{$account->user_name}}</td>
            <td class="text-center">{{$account->email}}</td>
            <td class="text-center">{{$account->role_name}}</td>
            <td width="10%" class="text-center"><span class="badge rounded-pill bg-label-success me-1">ใช้งาน</span></td>
            <td class="text-center" width="15%">
              @if($account->user_name != 'admin')
              <a href="{{ route('account.get', $account->user_id ) }}" class="btn btn-outline-warning btn-sm">แก้ไข</a>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="row pt-4">
        {{ $accounts->links('vendor.pagination.bootstrap-4') }}
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
<script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#account-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_account');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('account.create') }}",
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