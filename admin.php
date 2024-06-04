<?php
include 'auth.php';
include 'db.php';

// Oturum başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Rol kontrolü
checkRole(['admin', 'editor']);

// HTTP başlıklarında oturum ve rol bilgilerini göster
header("X-User-ID: " . $_SESSION['user_id']);
header("X-User-Role: " . $_SESSION['role']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="admin.php">Admin Paneli</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php?action=ilan_ekle">İlan Ekle</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php?action=ilan_listele">İlanları Listele</a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php?action=rol_guncelle">Kullanıcı Rolü Güncelle</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="cikis.php">Çıkış Yap</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <?php
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'ilan_ekle') {
                include 'ilan_ekle_formu.php';
            } elseif ($action == 'ilan_listele') {
                include 'ilan_listele.php';
            } elseif ($action == 'rol_guncelle' && $_SESSION['role'] == 'admin') {
                include 'rol_guncelle.php';
            }
        } else {
            echo "<h2 class='my-4'>Admin Paneline Hoşgeldiniz!</h2>";
            
            // Logları listele
            echo "<h3>Loglar</h3>";
            echo "<div class='logs' style='max-height: 400px; overflow-y: scroll;'>";
            $sql = "SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY logs.created_at DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='log-entry'>";
                    echo "<p><strong>" . htmlspecialchars($row['action']) . "</strong> - " . htmlspecialchars($row['details']) . " <em>(" . htmlspecialchars($row['created_at']) . ")</em></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Henüz log bulunmamaktadır.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>