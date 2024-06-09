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
        logAction($current_user_id, $action, $details);
        
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Kullanıcı Rolü Güncelle</h2>
            <?php if (!empty($error)) echo "<div class='bg-red-100 text-red-700 p-4 mb-4 rounded'>$error</div>"; ?>
            <?php if (!empty($success)) echo "<div class='bg-green-100 text-green-700 p-4 mb-4 rounded'>$success</div>"; ?>
            <form method="POST">
                <div class="mb-4">
                    <label for="user_id" class="block text-gray-700">Kullanıcı:</label>
                    <select class="w-full p-2 border border-gray-300 rounded mt-1" id="user_id" name="user_id" required>
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
                <div class="mb-4">
                    <label for="role" class="block text-gray-700">Yeni Rol:</label>
                    <select class="w-full p-2 border border-gray-300 rounded mt-1" id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="subscriber">Subscriber</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Rol Güncelle</button>
            </form>
        </div>
    </div>
</body>
</html>
