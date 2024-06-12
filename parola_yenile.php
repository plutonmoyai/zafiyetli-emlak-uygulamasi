<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hata ayıklama
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $username);
    
    if ($stmt->execute()) {
        $message = "Parola başarıyla güncellendi.";
        
        $current_user_id = $_SESSION['user_id'];
        $current_username = $_SESSION['username'];
        $action = "PAROLA DEĞİŞTİRİLDİ";
        $details = "$current_username adlı kullanıcı $username adlı kullanıcının parolasını değiştirdi.";
        logAction($current_user_id, $action, $details);
    } else {
        $message = "Parola güncellenirken bir hata oluştu: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
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
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Profil</h2>
            <?php if (!empty($message)) echo "<div class='bg-green-100 text-green-700 p-4 mb-4 rounded'>$message</div>"; ?>
            <form method="post">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Kullanıcı Adı:</label>
                    <input type="text" name="username" class="w-full p-2 border border-gray-300 rounded mt-1" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700">Yeni Parola:</label>
                    <input type="password" name="new_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Parolayı Güncelle</button>
            </form>
        </div>
    </div>
</body>
</html>