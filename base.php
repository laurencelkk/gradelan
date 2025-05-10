<?php
$servername = "gradelandb.cn2fakugthwj.us-east-1.rds.amazonaws.com"; //localhost
$username = "gradelan"; //root
$password = "gradelanaws123";
$database = "gradelandb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>