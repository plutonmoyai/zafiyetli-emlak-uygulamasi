<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';
checkRole(['admin', 'editor']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        addLog($current_user_id, $action, $details);

        echo "İlan başarıyla silindi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Geçersiz istek!";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Sil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">İlan Sil</h2>
        <a href="admin.php" class="btn btn-secondary">Geri Dön</a>
    </div>
</body>
</html>