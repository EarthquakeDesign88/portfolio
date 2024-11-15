@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขสิทธิผู้ตรวจงาน</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('authority.update', $authority->user_authority_id) }}" method="POST" id="authority-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="user_id" name="user_id" aria-label="Default select example">
                                    @foreach($accounts as $account)
                                    <option value="{{$account->user_id}}" {{$account->user_id == $authority->user_id ? 'selected' : '' }}>{{$account->first_name}} {{$account->last_name}}</option>
                                    @endforeach
                                </select>
                                <label for="user_id">เลือกผู้ตรวจงาน</label>
                                @error('user_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="location_old" name="location_old" value="{{$authority->zone_description}}_{{$authority->location_description}}" readonly/>
                                <label for="location_old">จุดตรวจเดิม</label>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="location" name="location" aria-label="Default select example">
                                    @foreach($locations as $location)
                                    <option value="{{$location->location_id}}" {{$location->location_id == $authority->user_location_id ? 'selected' : '' }}>{{$location->zone_description}}_{{$location->location_description}}</option>
                                    @endforeach
                                </select>
                                <label for="location">เลือกจุดตรวจ</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_authority" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
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
        $('#authority-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_authority');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('authority.update', $authority->user_authority_id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('authority') }}";
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