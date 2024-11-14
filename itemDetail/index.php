<?php
require '../config/dbcon.php';

$itemName = isset($_GET['itemName']) ? $_GET['itemName'] : '';

try {
    $stmt = $pdo->prepare("SELECT itemName, price, itemDescription, imageUrl, outOfStock FROM menuItems WHERE itemName = :itemName LIMIT 1");
    $stmt->bindParam(':itemName', $itemName, PDO::PARAM_STR);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
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
        .grayscale {
            filter: grayscale(100%);
        }

        .out-of-stock-label {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 30px;
            position: absolute;
            top: 36%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            z-index: 10;
            white-space: nowrap;
            max-width: 90%;
            text-overflow: ellipsis;
            font-size:24px;
        }

        #item-name {
            padding-left: 68px;
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 20px;
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
            height: 708px;
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
                margin: 36px 0 -8px -8px;
                width: 437px;
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
            <?php if ($item['outOfStock']): ?>
                <span class="out-of-stock-label">Sold Out for Today</span>
            <?php endif; ?>
            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $itemName; ?>" 
             class="<?php echo $item['outOfStock'] ? 'grayscale' : ''; ?>" />
            <div id="item-name"><?php echo $itemName; ?></div>
            <div id="item-price">₹ <?php echo $itemPrice; ?></div>
            <div id="item-description"><?php echo $itemDescription; ?></div>
        </div>
    </div>
</body>

</html>