<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'nampress';



try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Throwable $th) {
    throw $th;
}