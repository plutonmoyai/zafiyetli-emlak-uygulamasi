<?php
require_once 'db.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        echo json_encode(['role' => $user['role']]);
    } else {
        echo json_encode(['role' => '']);
    }

    $stmt->close();
    $conn->close();
}
?>