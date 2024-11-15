<?php
    // Enable CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include('../DB/connect.php');

    // Define the API endpoint
    $requestedEndpoint = '/backoffice/acs-app/api/announcement.php';
    $currentEndpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = 'SELECT * FROM announcements ORDER BY id ASC';
        
        $result = mysqli_query($connection, $query);
        
        if (!$result) {
            $error_message = 'Failed to retrieve data: ' . mysqli_error($connection);
            error_log('Failed to retrieve data: ' . $error_message);
            http_response_code(500);
            die($error_message);
        }
        
        $formattedResults = array();
        
        while ($row = mysqli_fetch_assoc($result)) {
            $row['id'] = (int)$row['id'];
            $row['totalThank'] = (int)$row['totalThank'];
            $row['totalView'] = (int)$row['totalView'];

            $formattedResults[] = $row;
        }
        
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($formattedResults);
    }

?>
