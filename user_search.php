<?php
include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ?");
    $searchParam = "%".$search."%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='bg-white p-4 rounded-lg shadow-md'>";
            echo "<h5 class='text-lg font-semibold'>" . htmlspecialchars($row['username']) . "</h5>";
            echo "<p class='text-gray-700'>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</p>";
            echo "<a href='user_profile.php?id=" . $row['id'] . "' class='text-white bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded mt-2 inline-block'>Profil Gör</a>";
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