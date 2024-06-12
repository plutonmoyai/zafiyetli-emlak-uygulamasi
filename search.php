<?php
include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE title LIKE ?");
    $searchParam = "%".$search."%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='bg-white p-4 rounded-lg shadow-md'>";
            if (!empty($row['image_path'])) {
                echo "<img src='" . htmlspecialchars($row['image_path']) . "' class='w-full h-48 object-cover rounded-md' alt='İlan Resmi'>";
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
            echo "<a href='ilan_detay.php?id=" . htmlspecialchars($row['id']) . "' class='text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded mt-2 inline-block'>Detayları Gör</a>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Sonuç bulunamadı.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Lütfen arama yapmak için bir şeyler yazın.</p>";
}

$conn->close();
?>