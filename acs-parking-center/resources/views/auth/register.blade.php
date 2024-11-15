@extends('layouts.master')

@section('content')

<style>
  .card {
    margin-top: 50px;
  }

  label {
    color: #fff;
  }

  .form-control {
    background-color: #fff;
    color: #000;
  }
</style>

<div class="header-advance-area">
  @include('partials.header')

  <div class="breadcome-area">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="breadcome-list">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="breadcomb-wp">
                  <div class="breadcomb-ctn">
                    <h2>
                      สร้างบัญชี
                    </h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">

        <div class="card-body">
          <form id="registerForm" method="post" action="javascript:void(0)">
            @csrf

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">ชื่อ</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" autocomplete="off" value="{{ old('name') }}">
                <div id="nameError"></div>
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">อีเมล</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" autocomplete="off" value="{{ old('email') }}">
                <div id="emailError"></div>
              </div>
            </div>

            <div class="form-group row">
              <label for="username" class="col-md-4 col-form-label text-md-right">บัญชีผู้ใช้</label>

              <div class="col-md-6">
                <input id="username" type="text" class="form-control" name="username"  autocomplete="off" value="{{ old('username') }}">
                <div id="usernameError"></div>
              </div>
            </div>

            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">รหัสผ่าน</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password"  autocomplete="off" value="{{ old('password') }}">
                <div id="passwordError"></div>
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  สร้างบัญชี
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('assets/lib/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
  function clearErrors() {
      $('.text-error').html('');
  }

  function clearRegisterForm() {
    $('#registerForm').trigger("reset");
    clearErrors();
  }


  function handleValidationErrors(errors, action) {
    $.each(errors, function(key, value) {
      if (key === 'name') {
        $('#nameError').html('<div class="text-error">' + value[0] + '</div>');
      } 
      else if (key === 'username') {
        $('#usernameError').html('<div class="text-error">' + value[0] + '</div>');
      } 
      else if (key === 'email') {
        $('#emailError').html('<div class="text-error">' + value[0] + '</div>');
      } 
      else if (key === 'password') {
        $('#passwordError').html('<div class="text-error">' + value[0] + '</div>');
      } 
    });
  }


  $("document").ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    $('#registerForm').submit(function(e) {
      clearErrors();
      e.preventDefault();
      var formData = new FormData(this);

      $.ajax({
        type: "POST",
        url: "{{ route('registerPerform') }}",
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(resp) {
          var status = resp.status;
          var message = resp.message;

          if (resp.status === "success") {
            Command: toastr["success"](message, null, {
              "showMethod": "slideDown",
              "hideMethod": "slideUp",
              "timeOut": 3000,
              "extendedTimeOut": 1000,
              "positionClass": "toast-top-right",
              "progressBar": true,
              "toastClass": "custom-toast"
            });

          }
          else {
            Command: toastr["error"](message, null, {
              "showMethod": "slideDown",
              "hideMethod": "slideUp",
              "timeOut": 3000,
              "extendedTimeOut": 1000,
              "positionClass": "toast-top-right",
              "progressBar": true,
              "toastClass": "custom-toast"
            });
          }
          clearRegisterForm();
        },
        error: function(resp) {
          var errors = resp.responseJSON.errors;
          handleValidationErrors(errors , 'register');
        }
      })
    });

  });
</script>

@endsection