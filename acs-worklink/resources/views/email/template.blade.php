<!DOCTYPE html>
<html>
<head>
    <title>Your Email</title>
</head>
<body>
    <h1>ชื่อ : {{ $data['first_name'] }}</h1>
    <h1>นามสกุล : {{ $data['last_name'] }}</h1>
    <h1>เบอร์โทร : {{ $data['mobile'] }}</h1>
    <h1>Email : {{ $data['email'] }}</h1>
</body>
</html>