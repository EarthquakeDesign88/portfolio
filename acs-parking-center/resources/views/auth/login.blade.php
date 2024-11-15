<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACS Parking</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/logo/logo-acs.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('{{ asset('assets/img/parking/login_bg.jpeg') }}');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333; 
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f9f9f9; 
        }

        .login-container input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .login-container .register-link {
            text-align: center;
            margin-top: 20px;
            color: #333; /* สีของข้อความลิงก์ */
        }

        .login-container .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container .register-link a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center; 
            color: #333;   
        }

        .alert-danger {
            margin-top: 5px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>ACS Parking</h1>
        <form action="{{ route('loginPerform') }}" method="POST">
            @csrf
            <input type="text" name="username" placeholder="ชื่อบัญชี" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <input type="submit" value="เข้าสู่ระบบ">
        </form>
        @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif          


    </div>
</body>
</html>
