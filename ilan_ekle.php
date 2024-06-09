include 'auth.php';
include 'db.php';
checkRole(['admin', 'editor']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $area = $_POST['area'];
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

    // Özellikleri JSON formatında saklama
    $features = isset($_POST['features']) ? json_encode($_POST['features']) : json_encode([]);

    if ($type == 'konut') {
        $rooms = $_POST['rooms'];
        $floors = $_POST['floors'];
        $building_age = $_POST['building_age'];

        $stmt = $conn->prepare("INSERT INTO properties (type, title, description, price, area, rooms, floors, building_age, address, city, district, image_path, user_id, features) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdidsissssis", $type, $title, $description, $price, $area, $rooms, $floors, $building_age, $address, $city, $district, $image_path, $user_id, $features);
    } elseif ($type == 'arsa') {
        $zoning_status = $_POST['zoning_status'];
        $land_type = $_POST['land_type'];

        $stmt = $conn->prepare("INSERT INTO properties (type, title, description, price, area, zoning_status, land_type, address, city, district, image_path, user_id, features) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdisssssssis", $type, $title, $description, $price, $area, $zoning_status, $land_type, $address, $city, $district, $image_path, $user_id, $features);
    } else {
        echo "Geçersiz ilan türü!";
        exit();
    }

    if ($stmt->execute()) {
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
            <a href="admin.php" class="text-blue-500 hover:underline">Geri Dön</a>
        </div>
    </div>
</body>
</html>