<?php
include 'db.php';
session_start();

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: giris.php");
        exit();
    }
}

redirectIfNotLoggedIn();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Geçersiz kullanıcı!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Profili</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">Ana Sayfa</a>
            <a href="cikis.php" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">Çıkış</a>
        </div>
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Kullanıcı Profili</h2>
            <p><strong>Kullanıcı Adı:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Ad:</strong> <?php echo $user['firstname']; ?></p>
            <p><strong>Soyad:</strong> <?php echo $user['lastname']; ?></p>
            <p><strong>Twitter:</strong> <?php echo $user['twitter']; ?></p>
            <p><strong>Instagram:</strong> <?php echo $user['instagram']; ?></p> <!-- XSS Zafiyeti -->
        </div>
    </div>
</body>
</html>