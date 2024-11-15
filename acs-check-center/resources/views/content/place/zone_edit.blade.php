@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขพื้นที่</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('zone.update', $zone->zone_id) }}" method="POST" id="zone-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="col-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="zone_desc" name="zone_desc" value="{{$zone->zone_description}}" placeholder="โปรดกรอกพื้นที่" aria-describedby="defaultFormControlHelp" />
                            <label for="zone_desc">พื้นที่</label> 
                            @error('zone_desc')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="save_edit_zone" class="btn btn-primary waves-effect waves-light" fdprocessedid="7s1on">อัพเดท</button>
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
        $('#zone-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_zone');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('zone.update', $zone->zone_id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('zones') }}";
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