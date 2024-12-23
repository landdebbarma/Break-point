<?php
include('config/dbcon.php');

header('Content-Type: application/json');

try {
    if (isset($_GET['request']) && $_GET['request'] === 'billboards') {
        // Fetch image URLs from the billboards table
        $query = "SELECT imageUrl FROM billboards";
        $stmt = $pdo->query($query);
        $images = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
            $imageSrc = htmlspecialchars($imageUrl);
            $images[] = $imageSrc;
        }

        echo json_encode($images);
    }
    
    // Fetch today's special menu item
    elseif (isset($_GET['request']) && $_GET['request'] === 'todays_special') {
        $stmt = $pdo->prepare("SELECT itemId, itemName, itemDescription, price, imageUrl FROM menuItems WHERE categoryName = :category LIMIT 1");
        $stmt->execute(['category' => "Special"]);
        
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $item['imageUrl']);
            $imageSrc = "" . htmlspecialchars($imageUrl);

            $data = [
                'itemId' => htmlspecialchars($item['itemId']),
                'itemName' => htmlspecialchars($item['itemName']),
                'itemDescription' => htmlspecialchars($item['itemDescription']),
                'price' => htmlspecialchars($item['price']),
                'imageUrl' => $imageSrc
            ];

            echo json_encode([$data]);
        } else {
            echo json_encode([]);
        }
    }

    // Fetch customer images
    elseif (isset($_GET['request']) && $_GET['request'] === 'customers') {
        $stmt = $pdo->query("SELECT customerNumber, imageUrl FROM Customers WHERE imageUrl IS NOT NULL");
        $customers = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
            $imageSrc ="". htmlspecialchars($imageUrl);
            $customers[] = [
                'customerNumber' => $row['customerNumber'],
                'imageUrl' => $imageSrc
            ];
        }

        echo json_encode($customers);
    }

    else {
        echo json_encode(['error' => 'Invalid request']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
