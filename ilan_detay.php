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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h2 class="text-2xl font-bold text-center"><?php echo htmlspecialchars($row['title']); ?></h2>
        <div class="bg-white p-6 rounded-lg shadow-md mb-4">
            <?php if (!empty($row['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="w-full h-64 object-cover rounded-md mb-4" alt="Ürün Resmi">
            <?php endif; ?>
            <div class="text-gray-700">
                <h5 class="text-xl font-semibold"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Fiyat:</strong> <?php echo htmlspecialchars($row['price']); ?> TL</p>
                <p><strong>Alan:</strong> <?php echo htmlspecialchars($row['area']); ?> m²</p>
                <?php if ($row['type'] == 'konut'): ?>
                    <p><strong>Oda Sayısı:</strong> <?php echo htmlspecialchars($row['rooms']); ?></p>
                    <p><strong>Kat Sayısı:</strong> <?php echo htmlspecialchars($row['floors']); ?></p>
                    <p><strong>Bina Yaşı:</strong> <?php echo htmlspecialchars($row['building_age']); ?></p>
                <?php elseif ($row['type'] == 'arsa'): ?>
                    <p><strong>İmar Durumu:</strong> <?php echo htmlspecialchars($row['zoning_status']); ?></p>
                    <p><strong>Arsa Tipi:</strong> <?php echo htmlspecialchars($row['land_type']); ?></p>
                <?php endif; ?>
                <p><strong>Adres:</strong> <?php echo htmlspecialchars($row['address'] . ", " . $row['district'] . ", " . $row['city']); ?></p>
                
                <!-- Özellikler -->
                <h4 class="text-lg font-semibold mt-4">Özellikler</h4>
                <?php if ($features): ?>
                    <ul class="list-disc list-inside">
                        <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Özellik bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
        <a href="index.php" class="block text-center text-blue-500 hover:underline">Geri Dön</a>
    </div>
</body>
</html>