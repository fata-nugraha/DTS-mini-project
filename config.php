<?php
    session_start();
    $host = "***********.us-east-1.rds.amazonaws.com"; /* Host name */
    $user = "********"; /* User */
    $password = "***********"; /* Password */
    $dbname = "dtstore"; /* Database name */
    $s3path = "https://************.s3.amazonaws.com";

    $mysqli = mysqli_connect($host, $user, $password, $dbname);
    // Check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }