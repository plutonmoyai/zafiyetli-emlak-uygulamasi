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
        $error = "Şifreler uyuşmuyor!";
    } else {
        $role = 'user';

        // Şifreyi düz metin olarak kaydediyoruz
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, firstname, lastname, twitter, instagram) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $email, $password, $role, $firstname, $lastname, $twitter, $instagram);

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kayıt Ol</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="firstname">Ad:</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname">Soyad:</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="twitter">Twitter:</label>
                <input type="text" name="twitter" class="form-control">
            </div>
            <div class="form-group">
                <label for="instagram">Instagram:</label>
                <input type="text" name="instagram" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Şifre Tekrar:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        </form>
        <div class="text-center mt-3">
            <a href="giris.php">Hesabınız var mı? Giriş Yapın!</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
