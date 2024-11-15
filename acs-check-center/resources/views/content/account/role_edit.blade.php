@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขบทบาท</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('role.update', $role->role_id) }}" method="POST" id="role-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="col-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="role_name" name="role_name" value="{{$role->role_name}}" placeholder="โปรดกรอกบทบาท" aria-describedby="defaultFormControlHelp" />
                            <label for="role_name">บทบาท</label>
                            @error('role_name')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_role" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
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
        $('#role-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_role');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('role.update', $role->role_id) }}",
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