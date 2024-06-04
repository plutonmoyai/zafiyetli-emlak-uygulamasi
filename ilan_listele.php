<?php
include 'auth.php';
include 'db.php';

// Oturum başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Rol kontrolü
checkRole(['admin', 'editor']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlan Listele</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Eklenen İlanlar</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM properties";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card'>";
                    if (!empty($row['image_path'])) {
                        echo "<img src='" . $row['image_path'] . "' class='card-img-top' alt='İlan Resmi'>";
                    }
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='card-text'><strong>Fiyat:</strong> " . htmlspecialchars($row['price']) . " TL</p>";
                    echo "<p class='card-text'><strong>Alan:</strong> " . htmlspecialchars($row['area']) . " m²</p>";
                    if ($row['type'] == 'konut') {
                        echo "<p class='card-text'><strong>Oda Sayısı:</strong> " . htmlspecialchars($row['rooms']) . "</p>";
                        echo "<p class='card-text'><strong>Kat Sayısı:</strong> " . htmlspecialchars($row['floors']) . "</p>";
                        echo "<p class='card-text'><strong>Bina Yaşı:</strong> " . htmlspecialchars($row['building_age']) . "</p>";
                    } elseif ($row['type'] == 'arsa') {
                        echo "<p class='card-text'><strong>İmar Durumu:</strong> " . htmlspecialchars($row['zoning_status']) . "</p>";
                        echo "<p class='card-text'><strong>Arsa Tipi:</strong> " . htmlspecialchars($row['land_type']) . "</p>";
                    }
                    echo "<p class='card-text'><strong>Adres:</strong> " . htmlspecialchars($row['address']) . ", " . htmlspecialchars($row['district']) . ", " . htmlspecialchars($row['city']) . "</p>";
                    echo "<a href='ilan_detay.php?id=" . $row['id'] . "' class='btn btn-primary'>Detayları Gör</a>";
                    echo "<a href='ilan_sil.php?id=" . $row['id'] . "' class='btn btn-danger'>Sil</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Henüz eklenen ilan bulunmamaktadır.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
