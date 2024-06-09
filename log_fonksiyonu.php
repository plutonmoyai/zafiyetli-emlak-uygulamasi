<?php
if (!function_exists('logAction')) {
    function logAction($user_id, $action, $description) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO logs (user_id, action, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $action, $description);
        $stmt->execute();
        $stmt->close();
    }
}
?>