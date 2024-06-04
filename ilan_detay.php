<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Geçersiz ilan ID'si!";
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM properties WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "İlan bulunamadı!";
    exit();
}

$row = $result->fetch_assoc();
$features = isset($row['features']) ? json_decode($row['features'], true) : [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Detayları</title>
    <link rel="stylesheet" href="https://stackpath.bootstrap.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center"><?php echo htmlspecialchars($row['title']); ?></h2>
        <div class="card mb-4">
            <?php if (!empty($row['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="Ürün Resmi">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                <p class="card-text"><strong>Fiyat:</strong> <?php echo htmlspecialchars($row['price']); ?> TL</p>
                <p class="card-text"><strong>Alan:</strong> <?php echo htmlspecialchars($row['area']); ?> m²</p>
                <?php if ($row['type'] == 'konut'): ?>
                    <p class="card-text"><strong>Oda Sayısı:</strong> <?php echo htmlspecialchars($row['rooms']); ?></p>
                    <p class="card-text"><strong>Kat Sayısı:</strong> <?php echo htmlspecialchars($row['floors']); ?></p>
                    <p class="card-text"><strong>Bina Yaşı:</strong> <?php echo htmlspecialchars($row['building_age']); ?></p>
                <?php elseif ($row['type'] == 'arsa'): ?>
                    <p class="card-text"><strong>İmar Durumu:</strong> <?php echo htmlspecialchars($row['zoning_status']); ?></p>
                    <p class="card-text"><strong>Arsa Tipi:</strong> <?php echo htmlspecialchars($row['land_type']); ?></p>
                <?php endif; ?>
                <p class="card-text"><strong>Adres:</strong> <?php echo htmlspecialchars($row['address'] . ", " . $row['district'] . ", " . $row['city']); ?></p>
                
                <!-- Özellikler -->
                <h4>Özellikler</h4>
                <?php if ($features): ?>
                    <ul>
                        <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Özellik bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
