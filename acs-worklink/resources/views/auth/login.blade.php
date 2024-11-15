
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ACS Worklink</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/acs-logo.png') }}">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">


</head>

<style>
    .login-form-bg {
        background-image: url('{{ asset('assets/images/login-bg.jpg') }}');
        background-size: cover;
        background-position: center;
    }

    .field-icon {
        float: right;
        margin-right: 10px;
        margin-top: -30px;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }

</style>

<body class="h-100">
    
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="#"> <h2>ACS Worklink</h2></a>
        
                                <form class="mt-5 mb-5 login-input" id="loginForm">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="user_name" id="userName" placeholder="กรุณากรอกบัญชีผู้ใช้">
                                        <div id="userNameError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="กรุณากรอกรหัสผ่าน">
                                        <span id="togglePassword" class="fa fa-fw fa-eye field-icon"></span>
                                        <div id="passwordError"></div>
                                    </div>
                                    <button class="btn login-form__btn submit w-100" type="submit" id="loginBtn">เข้าสู่ระบบ</button>
                                </form>
                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    
    <script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('assets/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/gleek.js') }}"></script>


    
    <script type="text/javascript">
        function clearErrors() {
            $('.text-error').html('');
        }

        function handleValidationErrors(errors) {
            $.each(errors, function(key, value) {
                if (key === 'user_name') {
                    $('#userNameError').html('<div class="text-error">' + value[0] + '</div>');
                } 
                else if (key === 'password') {
                    $('#passwordError').html('<div class="text-error">' + value[0] + '</div>');
                } 

            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "{{ route('postLogin') }}",
                    data: formData,
                    success: function (resp) {
                        if (resp.status == "success") {
                            window.location.href = "{{ route('dashboard') }}";
                        } 
                    },
                    error: function(resp) {
                        if (resp.status == 401) {
                            var errors = resp.responseJSON.message;
                            $('#userError').html('');
                            $('#passwordError').html('<div class="text-error">' + errors + '</div>');
                        } else if (resp.status == 422) {
                            var errors = resp.responseJSON.errors;
                            handleValidationErrors(errors);
                        }
                    }
                });
            });

            $('#togglePassword').on('click', function() {
                var passwordField = $('#password');
                var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });



    </script>
</body>
</html>





