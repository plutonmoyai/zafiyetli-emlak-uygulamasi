<?php
include 'auth.php';
include 'db.php';

// Oturum başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Rol kontrolü
checkRole(['admin', 'editor']);

// HTTP başlıklarında oturum ve rol bilgilerini göster
header("X-User-ID: " . $_SESSION['user_id']);
header("X-User-Role: " . $_SESSION['role']);

// Hata ayıklama modunu aktif et
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0">
                        <a href="admin.php" class="text-xl font-bold text-gray-700">Admin Paneli</a>
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="admin.php?action=ilan_ekle" class="text-gray-700 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">İlan Ekle</a>
                            <a href="admin.php?action=ilan_listele" class="text-gray-700 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">İlanları Listele</a>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <a href="admin.php?action=rol_guncelle" class="text-gray-700 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Kullanıcı Rolü Güncelle</a>
                            <?php endif; ?>
                            <a href="index.php" class="text-gray-700 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Ana Sayfa</a>

                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <a href="cikis.php" class="text-gray-700 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Çıkış Yap</a>
                </div>
            </div>
        </div>

        <div class="sm:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="admin.php?action=ilan_ekle" class="text-gray-700 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">İlan Ekle</a>
                <a href="admin.php?action=ilan_listele" class="text-gray-700 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">İlanları Listele</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="admin.php?action=rol_guncelle" class="text-gray-700 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Kullanıcı Rolü Güncelle</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10">
        <?php
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'ilan_ekle') {
                include 'ilan_ekle_formu.php';
            } elseif ($action == 'ilan_listele') {
                include 'ilan_listele.php';
            } elseif ($action == 'rol_guncelle' && $_SESSION['role'] == 'admin') {
                include 'rol_guncelle.php';
            }
        } else {
            echo "<h2 class='text-2xl font-bold mb-4'>Admin Paneline Hoşgeldiniz!</h2>";
            
            // Logları listele
            echo "<h3 class='text-xl font-semibold mb-4'>Loglar</h3>";
            echo "<div class='max-h-64 overflow-y-scroll bg-white p-4 rounded-lg shadow-md'>";
            $sql = "SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY logs.created_at DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='border-b border-gray-200 py-2'>";
                    echo "<p><strong>" . htmlspecialchars($row['action']) . "</strong> - " . htmlspecialchars($row['details']) . " <em>(" . htmlspecialchars($row['created_at']) . ")</em></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Henüz log bulunmamaktadır.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>