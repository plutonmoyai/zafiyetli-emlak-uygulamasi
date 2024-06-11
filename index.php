<?php
include 'db.php';
session_start();

// Ürün detaylarına giriş yapmadan önce kontrol
function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: giris.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ana Sayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: 'search.php',
                    type: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#ilanlar').html(data);
                    }
                });
            });
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Ana Sayfa</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cikis.php" class="text-white bg-red-500 hover:bg-red-600 px-3 py-2 rounded">Çıkış Yap</a>
            <?php else: ?>
                <div>
                    <a href="giris.php" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded mr-2">Giriş Yap</a>
                    <a href="kayit.php" class="text-white bg-green-500 hover:bg-green-600 px-3 py-2 rounded">Kayıt Ol</a>
                </div>
            <?php endif; ?>
        </div>
        <p class="text-center">Hoşgeldiniz<?php if (isset($_SESSION['user_id'])) echo ', ' . htmlspecialchars($_SESSION['username']); ?>!</p>
        
        <!-- Admin Paneli Butonunu Rol Kontrolü ile Göster -->
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')): ?>
            <div class="text-center mb-4">
                <a href="admin.php" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded">Admin Paneli</a>
            </div>
        <?php endif; ?>

        <input type="text" id="search" class="w-full p-2 border border-gray-300 rounded mt-1 mb-4" placeholder="Aramak için yazın...">

        <h3 class="my-4 text-xl font-semibold">Eklenen İlanlar</h3>
        <div id="ilanlar" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            $sql = "SELECT * FROM properties";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='bg-white p-4 rounded-lg shadow-md'>";
                    if (!empty($row['image_path'])) {
                        echo "<img src='" . htmlspecialchars($row['image_path']) . "' class='w-full h-48 object-cover rounded-md' alt='Ürün Resmi'>";
                    }
                    echo "<div class='p-4'>";
                    echo "<h5 class='text-lg font-semibold'>" . htmlspecialchars($row['title']) . "</h5>";
                    echo "<p class='text-gray-700'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='text-gray-700'><strong>Fiyat:</strong> " . htmlspecialchars($row['price']) . " TL</p>";
                    echo "<p class='text-gray-700'><strong>Alan:</strong> " . htmlspecialchars($row['area']) . " m²</p>";
                    if ($row['type'] == 'konut') {
                        echo "<p class='text-gray-700'><strong>Oda Sayısı:</strong> " . htmlspecialchars($row['rooms']) . "</p>";
                        echo "<p class='text-gray-700'><strong>Kat Sayısı:</strong> " . htmlspecialchars($row['floors']) . "</p>";
                        echo "<p class='text-gray-700'><strong>Bina Yaşı:</strong> " . htmlspecialchars($row['building_age']) . "</p>";
                    } elseif ($row['type'] == 'arsa') {
                        echo "<p class='text-gray-700'><strong>İmar Durumu:</strong> " . htmlspecialchars($row['zoning_status']) . "</p>";
                        echo "<p class='text-gray-700'><strong>Arsa Tipi:</strong> " . htmlspecialchars($row['land_type']) . "</p>";
                    }
                    echo "<p class='text-gray-700'><strong>Adres:</strong> " . htmlspecialchars($row['address']) . ", " . htmlspecialchars($row['district']) . ", " . htmlspecialchars($row['city']) . "</p>";
                    echo "<a href='ilan_detay.php?id=" . $row['id'] . "' class='text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded mt-2 inline-block'>Detayları Gör</a>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>