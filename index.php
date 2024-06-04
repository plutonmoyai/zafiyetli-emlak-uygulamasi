<?php
include 'db.php';
session_start();

// Ürün detaylarına giriş yapmadan önce kontrol
function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: giris.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center">Ana Sayfa</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cikis.php" class="btn btn-danger">Çıkış Yap</a>
            <?php endif; ?>
        </div>
        <p class="text-center">Hoşgeldiniz<?php if (isset($_SESSION['user_id'])) echo ', ' . $_SESSION['user_id']; ?>!</p>
        
        <!-- Admin Paneli Butonunu Rol Kontrolü ile Göster -->
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')): ?>
            <div class="text-center mb-4">
                <a href="admin.php" class="btn btn-primary">Admin Paneli</a>
            </div>
        <?php endif; ?>

        <h3 class="my-4">Eklenen İlanlar</h3>
        <div class="row">
            <?php
            $sql = "SELECT * FROM properties";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card'>";
                    if (!empty($row['image_path'])) {
                        echo "<img src='" . $row['image_path'] . "' class='card-img-top' alt='Ürün Resmi'>";
                    }
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $row['title'] . "</h5>";
                    echo "<p class='card-text'>" . $row['description'] . "</p>";
                    echo "<p class='card-text'><strong>Fiyat:</strong> " . $row['price'] . " TL</p>";
                    echo "<p class='card-text'><strong>Alan:</strong> " . $row['area'] . " m²</p>";
                    if ($row['type'] == 'konut') {
                        echo "<p class='card-text'><strong>Oda Sayısı:</strong> " . $row['rooms'] . "</p>";
                        echo "<p class='card-text'><strong>Kat Sayısı:</strong> " . $row['floors'] . "</p>";
                        echo "<p class='card-text'><strong>Bina Yaşı:</strong> " . $row['building_age'] . "</p>";
                    } elseif ($row['type'] == 'arsa') {
                        echo "<p class='card-text'><strong>İmar Durumu:</strong> " . $row['zoning_status'] . "</p>";
                        echo "<p class='card-text'><strong>Arsa Tipi:</strong> " . $row['land_type'] . "</p>";
                    }
                    echo "<p class='card-text'><strong>Adres:</strong> " . $row['address'] . ", " . $row['district'] . ", " . $row['city'] . "</p>";
                    echo "<a href='ilan_detay.php?id=" . $row['id'] . "' class='btn btn-primary'>Detayları Gör</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>