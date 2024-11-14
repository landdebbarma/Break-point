<?php
require '../config/dbcon.php'; // Ensure your database connection is correctly included here

// Get the item name from the URL
$itemName = isset($_GET['itemName']) ? $_GET['itemName'] : '';

try {
    // Prepare and execute a query to fetch the item's details
    $stmt = $pdo->prepare("SELECT itemName, price, itemDescription, imageUrl FROM menuItems WHERE itemName = :itemName LIMIT 1");
    $stmt->bindParam(':itemName', $itemName, PDO::PARAM_STR);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Sanitize data for safe output
        $itemName = htmlspecialchars($item['itemName']);
        $itemPrice = htmlspecialchars($item['price']);
        $itemDescription = htmlspecialchars($item['itemDescription']);
        $imageUrl = "../" . preg_replace('/^\.\.\/\.\.\//', '', $item['imageUrl']);
    } else {
        echo "Item not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching item details: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $itemName; ?> - BREAK POINT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            font-size: medium;
            background-color: #fff;
        }

        #item-name {
            padding-left: 68px;
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 23px;
        }

        #item-price {
            padding-left: 68px;
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 23px;
        }

        #item-description {
            padding-left: 68px;
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 23px;
        }

        .right-1 {
            margin: 118px 0 50px 100px;
            height: 754px;
            width: 500px;
            background-color: #c3c0c0;
        }

        .right-1 img {
            height: 450px;
            width: 500px;
            border-radius: 0 0 60px 60px;
        }

        @media (max-width: 700px) {
            .right-1 {
                margin: 36px 0 0 0;
                width: 434px;
            }

            .right-1 img {
                width: 434px;
                height: 500px;
            }
        }
    </style>
</head>

<body>
    <div class="item-container">
        <div class="right-1">
            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $itemName; ?>" />
            <div id="item-name"><?php echo $itemName; ?></div>
            <div id="item-price">₹<?php echo $itemPrice; ?></div>
            <div id="item-description"><?php echo $itemDescription; ?></div>
        </div>
    </div>
</body>

</html>