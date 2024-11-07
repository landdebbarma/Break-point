<?php
include '/xampp/htdocs/Break-point/config/dbcon.php';

// Fetch image URLs from the billboards table
try {
    $query = "SELECT imageUrl FROM billboards";
    $stmt = $pdo->query($query);
    $images = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
        $imageSrc = "" . htmlspecialchars($imageUrl);
        $images[] = $imageSrc;
    }
    header('Content-Type: application/json');
    echo json_encode($images);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>