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
        $stmt = $connection->prepare('SELECT qr_id, expiration_time, qr_data, log_status, log_time FROM qr_scanner WHERE qr_data = ? LIMIT 1');
        $stmt->bind_param('s', $qrData);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // There are existing records with the scanned QR data
            $row = $result->fetch_assoc();
            $latestQRId = $row['qr_id'];
            $latestQRData = $row['qr_data'];
            $statusQR = $row['status'];
            $currentTime = time();
            $expirationTime = strtotime($row['expiration_time']);

            if ($currentTime < $expirationTime) {
                if ($qrData === $latestQRData) {
                    if($statusQR == 'Authenticated successfully') {
                        $authStatus = 'QR code has been used';
                        $updateStmt = $connection->prepare('UPDATE qr_scanner SET log_status = ?, log_time = ? WHERE qr_data = ?');
                        $authTime = date('Y-m-d H:i:s', $currentTime);
                        $updateStmt->bind_param('sss', $authStatus, $authTime, $qrData);
                        $updateStmt->execute();
                        $updateStmt->close();

                        $response = [
                            'status' => 'QR code has been used',
                            'message' => 'QR code นี้ถูกใช้แล้ว โปรดสร้าง QR Code ใหม่'
                        ];

                        http_response_code(400);
                        echo json_encode($response); 
                    }
                    else {
                        $authStatus = 'Authenticated successfully';

                        $updateStmt = $connection->prepare('UPDATE qr_scanner SET status = ?, auth_time = ?, log_status = ?, log_time = ? WHERE qr_data = ?');
                        $authTime = date('Y-m-d H:i:s', $currentTime);
                        $updateStmt->bind_param('sss', $authStatus, $authTime, $authStatus, $authTime, $qrData);
                        $updateStmt->execute();
                        $updateStmt->close();

                        $response = [
                            'status' => 'Authenticated successfully',
                            'message' => 'ยืนยันตัวตนสำเร็จ'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    }          
                } 
                else {
                    $response = [
                        'status' => 'Invalid QR code',
                        'message' => 'QR Code นี้ไม่ถูกต้อง โปรดลองใหม่อีกครั้ง'
                    ];

                    http_response_code(400);
                    echo json_encode($response);
                }
            } else {
                $authStatus = 'QR code expired';
                $updateStmt = $connection->prepare('UPDATE qr_scanner SET log_status = ?, log_time = ? WHERE qr_data = ?');
                $authTime = date('Y-m-d H:i:s', $currentTime);
                $updateStmt->bind_param('sss', $authStatus, $authTime, $qrData);
                $updateStmt->execute();
                $updateStmt->close();

                $response = [
                    'status' => 'QR code expired',
                    'message' => 'QR Code หมดอายุ โปรดลองใหม่อีกครั้ง'
                ];

                http_response_code(400);
                echo json_encode($response);
            }
        } else {
            $response = [
                'status' => 'No information found',
                'message' => 'ไม่พบข้อมูล'
            ];

            http_response_code(404);
            echo json_encode($response);
        }

        $stmt->close();
    } else {
        $response = [
            'status' => 'waiting',
            'message' => 'Method Not Allowed'
        ];

        http_response_code(405);
        echo json_encode($response);
    }
