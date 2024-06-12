<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';

// Oturum başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

$profileMessage = '';

// Kullanıcı oturum açmış mı kontrol et
if (!isset($_SESSION['username'])) {
    header("Location: giris.php");
    exit();
}

// Profil bilgilerini veritabanından çek
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT firstname, lastname, twitter, instagram FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['twitter']) || isset($_POST['instagram'])) {
        // Profil bilgilerini güncelleme işlemi
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $twitter = $_POST['twitter'];
        $instagram = $_POST['instagram'];
        
        $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, twitter = ?, instagram = ? WHERE username = ?");
        $stmt->bind_param("sssss", $firstname, $lastname, $twitter, $instagram, $username);
        
        if ($stmt->execute()) {
            $profileMessage = "Profil bilgileri başarıyla güncellendi.";
            
            // Log ekle
            $current_user_id = $_SESSION['user_id'];
            $current_username = $_SESSION['username'];
            $action = "PROFIL GÜNCELLENDİ";
            $details = "$current_username adlı kullanıcı kendi profil bilgilerini güncelledi.";
            logAction($current_user_id, $action, $details);

            // Güncellenen bilgileri tekrar çek
            $stmt = $conn->prepare("SELECT firstname, lastname, twitter, instagram FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $profile = $result->fetch_assoc();
        } else {
            $profileMessage = "Profil bilgileri güncellenirken bir hata oluştu: " . $stmt->error;
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">Ana Sayfa</a>
            <a href="cikis.php" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">Çıkış</a>
        </div>
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Profil</h2>
            <?php if (!empty($profileMessage)) echo "<div class='bg-green-100 text-green-700 p-4 mb-4 rounded'>$profileMessage</div>"; ?>
            <form method="post">
                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700">Ad:</label>
                    <input type="text" name="firstname" class="w-full p-2 border border-gray-300 rounded mt-1" value="<?php echo htmlspecialchars($profile['firstname']); ?>">
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700">Soyad:</label>
                    <input type="text" name="lastname" class="w-full p-2 border border-gray-300 rounded mt-1" value="<?php echo htmlspecialchars($profile['lastname']); ?>">
                </div>
                <div class="mb-4">
                    <label for="twitter" class="block text-gray-700">Twitter:</label>
                    <input type="text" name="twitter" class="w-full p-2 border border-gray-300 rounded mt-1" value="<?php echo htmlspecialchars($profile['twitter']); ?>">
                </div>
                <div class="mb-4">
                    <label for="instagram" class="block text-gray-700">Instagram:</label>
                    <input type="text" name="instagram" class="w-full p-2 border border-gray-300 rounded mt-1" value="<?php echo htmlspecialchars($profile['instagram']); ?>">
                </div>
                <button type="submit" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">Profil Bilgilerini Güncelle</button>
            </form>
            <div class="text-center mt-4">
                <a href="parola_yenile.php" class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-2 rounded inline-block">Parola Güncelle</a>
            </div>
        </div>
    </div>
</body>
</html>