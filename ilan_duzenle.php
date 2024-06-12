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
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $district = $_POST['district'];

    $image_path = $property['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "Resim yükleme hatası!";
            exit();
        }
    }

    if ($type == 'konut') {
        $rooms = $_POST['rooms'];
        $floors = $_POST['floors'];
        $building_age = $_POST['building_age'];

        $stmt = $conn->prepare("UPDATE properties SET type = ?, title = ?, description = ?, price = ?, area = ?, rooms = ?, floors = ?, building_age = ?, address = ?, city = ?, district = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssdidsissssi", $type, $title, $description, $price, $area, $rooms, $floors, $building_age, $address, $city, $district, $image_path, $id);
    } elseif ($type == 'arsa') {
        $zoning_status = $_POST['zoning_status'];
        $land_type = $_POST['land_type'];

        $stmt = $conn->prepare("UPDATE properties SET type = ?, title = ?, description = ?, price = ?, area = ?, zoning_status = ?, land_type = ?, address = ?, city = ?, district = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssdissssssi", $type, $title, $description, $price, $area, $zoning_status, $land_type, $address, $city, $district, $image_path, $id);
    } else {
        echo "Geçersiz ilan türü!";
        exit();
    }

    if ($stmt->execute()) {
        // Log ekle
        $current_user_id = $_SESSION['user_id'];
        $current_username = $_SESSION['username'];
        $action = "İLAN GÜNCELLENDİ";
        $details = "$current_username adlı kullanıcı '$title' ilanını güncelledi.";
        logAction($current_user_id, $action, $details);

        $_SESSION['response'] = array(
            'message' => 'İlan başarıyla güncellendi.',
            'type' => 'success'
        );
        header("Location: admin.php?action=ilan_listele");
        exit();
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">İlan Düzenle</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
                <div class="mb-4">
                    <label for="type" class="block text-gray-700">İlan Tipi:</label>
                    <select class="w-full p-2 border border-gray-300 rounded mt-1" id="type" name="type" required>
                        <option value="konut" <?php if ($property['type'] == 'konut') echo 'selected'; ?>>Konut</option>
                        <option value="arsa" <?php if ($property['type'] == 'arsa') echo 'selected'; ?>>Arsa</option>
                    </select>
                </div>
                <div id="konutFields" style="<?php if ($property['type'] != 'konut') echo 'display: none;'; ?>">
                    <div class="mb-4">
                        <label for="rooms" class="block text-gray-700">Oda Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="rooms" name="rooms" value="<?php echo $property['rooms']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="floors" class="block text-gray-700">Kat Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="floors" name="floors" value="<?php echo $property['floors']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="building_age" class="block text-gray-700">Bina Yaşı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="building_age" name="building_age" value="<?php echo $property['building_age']; ?>">
                    </div>
                </div>
                <div id="arsaFields" style="<?php if ($property['type'] != 'arsa') echo 'display: none;'; ?>">
                    <div class="mb-4">
                        <label for="zoning_status" class="block text-gray-700">İmar Durumu:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="zoning_status" name="zoning_status" value="<?php echo $property['zoning_status']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="land_type" class="block text-gray-700">Arsa Tipi:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="land_type" name="land_type" value="<?php echo $property['land_type']; ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Başlık:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="title" name="title" value="<?php echo $property['title']; ?>">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Açıklama:</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded mt-1" id="description" name="description"><?php echo $property['description']; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Fiyat:</label>
                    <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="price" name="price" value="<?php echo $property['price']; ?>" step="0.01">
                </div>
                <div class="mb-4">
                    <label for="area" class="block text-gray-700">Alan (m²):</label>
                    <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="area" name="area" value="<?php echo $property['area']; ?>" step="0.01">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Adres:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="address" name="address" value="<?php echo $property['address']; ?>">
                </div>
                <div class="mb-4">
                    <label for="city" class="block text-gray-700">Şehir:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="city" name="city" value="<?php echo $property['city']; ?>">
                </div>
                <div class="mb-4">
                    <label for="district" class="block text-gray-700">İlçe:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="district" name="district" value="<?php echo $property['district']; ?>">
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">Resim:</label>
                    <input type="file" class="w-full p-2 border border-gray-300 rounded mt-1" id="image" name="image">
                    <?php if (!empty($property['image_path'])): ?>
                        <img src="<?php echo $property['image_path']; ?>" class="w-full h-48 object-cover rounded mt-2" alt="İlan Resmi">
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Özellikler:</label>
                    <div class="flex items-center">
                        <?php
                        $features = [];
                        $feature_stmt = $conn->prepare("SELECT feature FROM property_features WHERE property_id = ?");
                        $feature_stmt->bind_param("i", $property['id']);
                        $feature_stmt->execute();
                        $feature_result = $feature_stmt->get_result();
                        while ($feature_row = $feature_result->fetch_assoc()) {
                            $features[] = $feature_row['feature'];
                        }
                        $feature_stmt->close();
                        ?>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="ADSL" class="mr-1" <?php if (in_array('ADSL', $features)) echo 'checked'; ?>> ADSL
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="Ahşap Doğrama" class="mr-1" <?php if (in_array('Ahşap Doğrama', $features)) echo 'checked'; ?>> Ahşap Doğrama
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="Araç Şarj İstasyonu" class="mr-1" <?php if (in_array('Araç Şarj İstasyonu', $features)) echo 'checked'; ?>> Araç Şarj İstasyonu
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="24 Saat Güvenlik" class="mr-1" <?php if (in_array('24 Saat Güvenlik', $features)) echo 'checked'; ?>> 24 Saat Güvenlik
                        </label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Güncelle</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('type').addEventListener('change', function() {
            var konutFields = document.getElementById('konutFields');
            var arsaFields = document.getElementById('arsaFields');
            if (this.value == 'konut') {
                konutFields.style.display = 'block';
                arsaFields.style.display = 'none';
            } else if (this.value == 'arsa') {
                konutFields.style.display = 'none';
                arsaFields.style.display = 'block';
            } else {
                konutFields.style.display = 'none';
                arsaFields.style.display = 'none';
            }
        });
    </script>
</body>
</html>