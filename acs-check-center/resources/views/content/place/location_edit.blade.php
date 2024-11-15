@extends('layouts/contentNavbarLayout')

@section('title', config('variables.appName'))

@section('content')

<div class="card">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">แก้ไขจุดตรวจ</h5>
            <div class="card-body" style="padding-bottom: 8px;">
                <form action="{{ route('location.update', $location->location_id) }}" method="POST" id="location-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="location_description" name="location_description" value="{{$location->location_description}}" placeholder="กรุณากรอกจุดตรวจ" aria-describedby="defaultFormControlHelp" />
                                <label for="location_description">จุดตรวจ</label>
                                @error('location_description')
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
                                <select class="form-select" id="location_zone" name="location_zone" value="{{$location->location_zone_id}}" aria-label="Default select example">
                                    @foreach($zones as $zone)
                                    <option value="{{$zone->zone_id}}" {{$zone->zone_id == $location->location_zone_id ? 'selected' : '' }}>
                                        {{ $zone->zone_description }}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="location_zone">พื้นที่</label>
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
        $('#location-edit-form').on('submit', function(event) {
            event.preventDefault();

            var $submitButton = $('#save_edit_point');
            $submitButton.prop('disabled', true);

            $.ajax({
                url: "{{ route('location.update', $location->location_id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            text: response.message,
                            icon: response.status
                        }).then((result) => {
                            window.location.href = "{{ route('locations') }}";
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