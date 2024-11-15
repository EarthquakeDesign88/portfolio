<?php
    // Enable CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    include('../DB/connect.php');


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get and sanitize the qr_data parameter from the URL
        $qrData = isset($_GET['qr_data']) ? mysqli_real_escape_string($connection, $_GET['qr_data']) : '';

        if (!empty($qrData)) {
            $stmt = $connection->prepare('SELECT qr_id, qr_data, status FROM qr_scanner WHERE qr_data = ?');
            $stmt->bind_param('s', $qrData);
            $stmt->execute();
            
            $stmt->bind_result($qr_id, $qr_data, $status);

            if ($stmt->fetch()) {
                $response = [
                    'status' => $status,
                    'message' => ($status == 'Authenticated successfully') ? 'ยืนยันตัวตนสำเร็จ' : 'รอยืนยันตัวตน'
                ];

                http_response_code(200);
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'No records found'
                ]);
            }

            $stmt->close();
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    } else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    }

    // Close the database connection (if not using a connection pool)
    mysqli_close($connection);
