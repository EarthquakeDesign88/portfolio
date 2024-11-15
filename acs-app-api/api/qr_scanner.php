<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include('../DB/connect.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $jsonData = file_get_contents('php://input');
    $requestData = json_decode($jsonData, true);

    // Extract the necessary data from the JSON
    $qrData = $requestData['qrData'];

    // Prepare and execute the database query
    $stmt = $connection->prepare('SELECT qr_id, expiration_time, qr_data, status FROM qr_scanner WHERE qr_data = ? LIMIT 1');
    if ($stmt === false) {
        // If preparation failed, output the error
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $connection->error]);
        exit;
    }

    $stmt->bind_param('s', $qrData);
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($qr_id, $expiration_time, $qr_data_result, $status);

    $response = [];
    
    if ($stmt->fetch()) {
        // There are existing records with the scanned QR data
        $currentTime = time();
        $expirationTime = strtotime($expiration_time);

        if ($currentTime < $expirationTime) {
            if ($qrData === $qr_data_result) {
                if ($status == 'Authenticated successfully') {
                    $response = [
                        'status' => 'QR code has been used',
                        'message' => 'QR code นี้ถูกใช้แล้ว โปรดสร้าง QR Code ใหม่'
                    ];

                    http_response_code(400);
                } else {
                    $authStatus = 'Authenticated successfully';
                    $authTime = date('Y-m-d H:i:s', $currentTime);
                    
                    // Close the initial statement
                    $stmt->close();

                    // Prepare and execute the update statement
                    $updateStmt = $connection->prepare('UPDATE qr_scanner SET status = ?, auth_time = ? WHERE qr_data = ?');
                    if ($updateStmt === false) {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update statement: ' . $connection->error]);
                        exit;
                    }
                    
                    $updateStmt->bind_param('sss', $authStatus, $authTime, $qrData);
                    $updateStmt->execute();
                    $updateStmt->close();

                    $response = [
                        'status' => 'Authenticated successfully',
                        'message' => 'ยืนยันตัวตนสำเร็จ'
                    ];

                    http_response_code(200);
                }
            }
        } else {
            $response = [
                'status' => 'QR code expired',
                'message' => 'QR Code หมดอายุ โปรดลองใหม่อีกครั้ง'
            ];

            http_response_code(400);
        }
    } else {
        $response = [
            'status' => 'Invalid QR code',
            'message' => 'QR Code นี้ไม่ถูกต้อง โปรดลองใหม่อีกครั้ง'
        ];

        http_response_code(404);
    }

    echo json_encode($response);
} else {
    $response = [
        'status' => 'waiting',
        'message' => 'Method Not Allowed'
    ];

    http_response_code(405);
    echo json_encode($response);
}
