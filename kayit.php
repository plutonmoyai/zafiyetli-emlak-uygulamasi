<?php
require_once 'db.php';
session_start();

// Eğer kullanıcı zaten giriş yapmışsa, index.php sayfasına yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $error = "Parolalar uyuşmuyor!";
    } else {
        $role = 'user';

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, firstname, lastname, twitter, instagram) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $email, $hashed_password, $role, $firstname, $lastname, $twitter, $instagram);

        if ($stmt->execute()) {
            header("Location: giris.php");
            exit();
        } else {
            $error = "Kayıt başarısız: " . $conn->error;
        }
    }

    // Hata mesajını session'a kaydedin ve sayfayı yeniden yükleyin
    $_SESSION['error'] = $error;
    header("Location: kayit.php");
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
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Kayıt Ol</h2>
            <?php if (!empty($error)) echo "<div class='bg-red-100 text-red-700 p-4 mb-4 rounded'>$error</div>"; ?>
            <form method="post">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Kullanıcı Adı:</label>
                    <input type="text" name="username" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">E-posta:</label>
                    <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700">Ad:</label>
                    <input type="text" name="firstname" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700">Soyad:</label>
                    <input type="text" name="lastname" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="twitter" class="block text-gray-700">Twitter:</label>
                    <input type="text" name="twitter" class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <div class="mb-4">
                    <label for="instagram" class="block text-gray-700">Instagram:</label>
                    <input type="text" name="instagram" class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Parola:</label>
                    <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700">Parola Tekrar:</label>
                    <input type="password" name="confirm_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Kayıt Ol</button>
            </form>
            <div class="text-center mt-4">
                <a href="giris.php" class="text-blue-500 hover:underline">Hesabınız var mı? Giriş Yapın!</a>
            </div>
        </div>
    </div>
</body>
</html>
