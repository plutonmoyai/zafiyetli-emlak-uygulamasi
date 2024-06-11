<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';
checkRole(['admin', 'editor']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = !empty($_POST['price']) ? $_POST['price'] : 0;
    $area = !empty($_POST['area']) ? $_POST['area'] : 0;
    $address = $_POST['address'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "Resim yükleme hatası!";
            exit();
        }
    }

    if ($type == 'konut') {
        $rooms = !empty($_POST['rooms']) ? $_POST['rooms'] : 0;
        $floors = !empty($_POST['floors']) ? $_POST['floors'] : 0;
        $building_age = !empty($_POST['building_age']) ? $_POST['building_age'] : 0;

        $stmt = $conn->prepare("INSERT INTO properties (type, title, description, price, area, rooms, floors, building_age, address, city, district, image_path, user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdidsissssi", $type, $title, $description, $price, $area, $rooms, $floors, $building_age, $address, $city, $district, $image_path, $user_id);
    } elseif ($type == 'arsa') {
        $zoning_status = !empty($_POST['zoning_status']) ? $_POST['zoning_status'] : '';
        $land_type = !empty($_POST['land_type']) ? $_POST['land_type'] : '';

        $stmt = $conn->prepare("INSERT INTO properties (type, title, description, price, area, zoning_status, land_type, address, city, district, image_path, user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdissssssi", $type, $title, $description, $price, $area, $zoning_status, $land_type, $address, $city, $district, $image_path, $user_id);
    } else {
        echo "Geçersiz ilan türü!";
        exit();
    }

    if ($stmt->execute()) {
        $property_id = $stmt->insert_id;

        if (isset($_POST['features'])) {
            foreach ($_POST['features'] as $feature) {
                $feature_stmt = $conn->prepare("INSERT INTO property_features (property_id, feature) VALUES (?, ?)");
                $feature_stmt->bind_param("is", $property_id, $feature);
                $feature_stmt->execute();
                $feature_stmt->close();
            }
        }

        // Log ekle
        $current_user_id = $_SESSION['user_id'];
        $current_username = $_SESSION['username'];
        $action = "İLAN OLUŞTURULDU";
        $details = "$current_username adlı kullanıcı '$title' ilanını oluşturdu.";
        logAction($current_user_id, $action, $details);

        echo "Yeni ilan başarıyla eklendi.";
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
    <title>İlan Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">İlan Ekle</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="type" class="block text-gray-700">İlan Tipi:</label>
                    <select class="w-full p-2 border border-gray-300 rounded mt-1" id="type" name="type" required>
                        <option value="">Seçiniz</option>
                        <option value="konut">Konut</option>
                        <option value="arsa">Arsa</option>
                    </select>
                </div>
                <div id="konutFields" style="display: none;">
                    <div class="mb-4">
                        <label for="rooms" class="block text-gray-700">Oda Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="rooms" name="rooms">
                    </div>
                    <div class="mb-4">
                        <label for="floors" class="block text-gray-700">Kat Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="floors" name="floors">
                    </div>
                    <div class="mb-4">
                        <label for="building_age" class="block text-gray-700">Bina Yaşı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="building_age" name="building_age">
                    </div>
                </div>
                <div id="arsaFields" style="display: none;">
                    <div class="mb-4">
                        <label for="zoning_status" class="block text-gray-700">İmar Durumu:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="zoning_status" name="zoning_status">
                    </div>
                    <div class="mb-4">
                        <label for="land_type" class="block text-gray-700">Arsa Tipi:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="land_type" name="land_type">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Başlık:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="title" name="title">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Açıklama:</label>
                    <textarea class="w-full p-2 border border-gray-300 rounded mt-1" id="description" name="description"></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Fiyat:</label>
                    <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="price" name="price" step="0.01">
                </div>
                <div class="mb-4">
                    <label for="area" class="block text-gray-700">Alan (m²):</label>
                    <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="area" name="area" step="0.01">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Adres:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="address" name="address">
                </div>
                <div class="mb-4">
                    <label for="city" class="block text-gray-700">Şehir:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="city" name="city">
                </div>
                <div class="mb-4">
                    <label for="district" class="block text-gray-700">İlçe:</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="district" name="district">
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">Resim:</label>
                    <input type="file" class="w-full p-2 border border-gray-300 rounded mt-1" id="image" name="image">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Özellikler:</label>
                    <div class="flex items-center">
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="ADSL" class="mr-1"> ADSL
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="Ahşap Doğrama" class="mr-1"> Ahşap Doğrama
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="Araç Şarj İstasyonu" class="mr-1"> Araç Şarj İstasyonu
                        </label>
                        <label class="mr-2">
                            <input type="checkbox" name="features[]" value="24 Saat Güvenlik" class="mr-1"> 24 Saat Güvenlik
                        </label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">İlan Ekle</button>
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