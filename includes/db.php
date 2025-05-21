<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'todoapp';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
