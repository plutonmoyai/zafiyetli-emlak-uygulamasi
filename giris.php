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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Giriş Yap</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group" hidden>
                <input type="hidden" name="role" class="form-control" value="" required>
            </div>
            <button type="submit" class="btn btn-primary">Giriş Yap</button>
        </form>
        <div class="text-center mt-3">
            <a href="kayit.php">Hesabınız yok mu? Kayıt Olun!</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
