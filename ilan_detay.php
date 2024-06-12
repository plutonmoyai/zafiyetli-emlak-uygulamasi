<?php
include 'db.php';

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // İlan detaylarını getir
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    $stmt->close();

    if ($property) {
        // Özellikleri getir
        $features_stmt = $conn->prepare("SELECT feature FROM property_features WHERE property_id = ?");
        $features_stmt->bind_param("i", $id);
        $features_stmt->execute();
        $features_result = $features_stmt->get_result();
        $features = [];
        while ($row = $features_result->fetch_assoc()) {
            $features[] = $row['feature'];
        }
        $features_stmt->close();
    } else {
        echo "İlan bulunamadı!";
        exit();
    }
} else {
    echo "Geçersiz istek!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Detayları</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-3xl font-bold mb-4"><?php echo $property['title']; ?></h2>
            <?php if (!empty($property['image_path'])): ?>
                <img src="<?php echo $property['image_path']; ?>" alt="İlan Resmi" class="w-full h-64 object-cover mb-4">
            <?php endif; ?>
            <p class="mb-4"><?php echo nl2br($property['description']); ?></p>
            <p class="mb-4"><strong>Fiyat:</strong> <?php echo number_format($property['price'], 2); ?> TL</p>
            <p class="mb-4"><strong>Alan:</strong> <?php echo $property['area']; ?> m²</p>
            <?php if ($property['type'] == 'konut'): ?>
                <p class="mb-4"><strong>Oda Sayısı:</strong> <?php echo $property['rooms']; ?></p>
                <p class="mb-4"><strong>Kat Sayısı:</strong> <?php echo $property['floors']; ?></p>
                <p class="mb-4"><strong>Bina Yaşı:</strong> <?php echo $property['building_age']; ?></p>
            <?php elseif ($property['type'] == 'arsa'): ?>
                <p class="mb-4"><strong>İmar Durumu:</strong> <?php echo $property['zoning_status']; ?></p>
                <p class="mb-4"><strong>Arsa Tipi:</strong> <?php echo $property['land_type']; ?></p>
            <?php endif; ?>
            <p class="mb-4"><strong>Adres:</strong> <?php echo $property['address']; ?>, <?php echo $property['district']; ?>, <?php echo $property['city']; ?></p>
            <p class="mb-4"><strong>Özellikler:</strong></p>
            <ul class="list-disc list-inside">
                <?php foreach ($features as $feature): ?>
                    <li><?php echo $feature; ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="index.php" class="inline-block mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Geri Dön</a>
        </div>
    </div>
</body>
</html>