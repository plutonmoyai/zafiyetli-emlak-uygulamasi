<?php
// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
session_start();

// Eğer kullanıcı zaten giriş yapmışsa, index.php sayfasına yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($_POST['role'] != ''){
        $role = $_POST['role'];
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Hazırlık hatası: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Şifre doğrulamasını düz metin olarak yapıyoruz
        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            if ($role) {
                $_SESSION['role'] = $role;
            } else {
                $_SESSION['role'] = $user['role'];
            }
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre!";
        }
    } catch (Exception $e) {
        $error = "Bir hata oluştu: " . $e->getMessage();
    }

    // Hata mesajını session'a kaydedin ve sayfayı yeniden yükleyin
    $_SESSION['error'] = $error;
    header("Location: giris.php");
    exit();
}

// Hata mesajını session'dan alıp gösterin
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Giriş Yap</h2>
            <?php if (!empty($error)) echo "<div class='bg-red-100 text-red-700 p-4 mb-4 rounded'>$error</div>"; ?>
            <form method="post">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Kullanıcı Adı:</label>
                    <input type="text" name="username" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Şifre:</label>
                    <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4" hidden>
                    <input type="hidden" name="role" class="form-control" value="" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Giriş Yap</button>
            </form>
            <div class="text-center mt-3">
                <a href="kayit.php" class="text-blue-500 hover:underline">Hesabınız yok mu? Kayıt Olun!</a>
            </div>
        </div>
    </div>
</body>
</html>