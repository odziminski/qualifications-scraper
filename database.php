<?php
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USER'];

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db", $user);
} catch (PDOException $e) {
    exit('Error connecting to database: ' . $e->getMessage());
}