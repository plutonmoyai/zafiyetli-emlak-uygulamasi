<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "906711";
$dbname = "emlak_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!function_exists('logAction')) {
    function logAction($user_id, $action, $description) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $action, $description);
        $stmt->execute();
        $stmt->close();
    }
}
?>