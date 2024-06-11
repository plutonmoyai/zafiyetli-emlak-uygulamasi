<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';
checkRole(['admin', 'editor']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["status" => "error", "message" => "Geçersiz istek!"];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // İlan başlığını al
    $stmt = $conn->prepare("SELECT title FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $stmt->close();

    // İlanı sil
    $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Log ekle
        $current_user_id = $_SESSION['user_id'];
        $current_username = $_SESSION['username'];
        $action = "İLAN SİLİNDİ";
        $details = "$current_username adlı kullanıcı '$title' ilanını sildi.";
        logAction($current_user_id, $action, $details);

        $response = ["status" => "success", "message" => "İlan başarıyla silindi."];
    } else {
        $response["message"] = "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

$_SESSION['response'] = $response;
header('Location: admin.php?action=ilan_listele');
exit();
?>