<?php
    $host = "localhost";
	$user = "root";
	$password = "Admin@88#";
	$database = "acs_community";


    $connection = mysqli_connect($host, $user, $password, $database);
    if (!$connection) {
        die('Failed to connect to the database: ' . mysqli_connect_error());
    }

    mysqli_set_charset($connection, 'utf8');
    date_default_timezone_set('Asia/Bangkok');
