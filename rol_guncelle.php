<?php
include 'auth.php';
include 'db.php';
include 'log_fonksiyonu.php';
checkRole(['admin']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $user_id);
    
    if ($stmt->execute()) {
        // Log ekle
        $current_user_id = $_SESSION['user_id'];
        $current_username = $_SESSION['username'];
        $user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_data = $user_result->fetch_assoc();
        $target_username = $user_data['username'];
        $action = "ROL DÜZENLENDİ";
        $details = "$current_username rolündeki kullanıcı, $target_username adlı kullanıcının rolünü $role olarak düzenledi.";
        addLog($current_user_id, $action, $details);
        
        // Eğer kendi rolünü değiştirdiysen, oturum rolünü de güncelle
        if ($user_id == $_SESSION['user_id']) {
            $_SESSION['role'] = $role;
            // Rol değiştiğinde kontrol edip yönlendirme yapalım
            if ($role != 'admin') {
                header("Location: index.php");
                exit();
            }
        }
        $success = "Kullanıcı rolü başarıyla güncellendi.";
    } else {
        $error = "Hata: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Rolü Güncelle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Kullanıcı Rolü Güncelle</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="user_id">Kullanıcı:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <?php
                    $sql = "SELECT id, username, role FROM users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['username'] . " (Şu anki rolü: " . $row['role'] . ")</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="role">Yeni Rol:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="subscriber">Subscriber</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Rol Güncelle</button>
        </form>
    </div>
</body>
</html>
