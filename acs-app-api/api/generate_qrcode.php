<?php
    // Enable CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");


    include('../DB/connect.php');
    
    // Define the API endpoint for inserting data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            die('Invalid JSON data');
        }

        $qrdata = $data['qrdata'];
        $status = $data['status'];
        $action = $data['action'];
        $generate_time = $data['generate_time'];
        $expiration_time = $data['expiration_time'];

        $insertQuery = 'INSERT INTO qr_scanner (qr_data, status, action, generate_time, expiration_time) VALUES (?, ?, ?, ?, ?)';
        $statement = mysqli_prepare($connection, $insertQuery);

        mysqli_stmt_bind_param($statement, 'sssss', $qrdata, $status, $action,  $generate_time, $expiration_time);

        if (mysqli_stmt_execute($statement)) {
            echo 'Data inserted successfully';
            http_response_code(200);
        } else {
            http_response_code(500);
            echo 'Failed to insert data: ' . mysqli_error($connection);
        }

        mysqli_stmt_close($statement);
    }
