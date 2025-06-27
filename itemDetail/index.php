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
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title><?php echo $itemName; ?> - BREAK POINT</title>
    <style>
        body {
            font-family: 'Trirong', sans-serif;
        }

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
            font-size: 24px;
        }

        #item-name {
            display: flex;
            justify-content: space-around;
            /* padding-left: 68px; */
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bolder;
            font-size: 24px;
        }

        #item-price {
            display: flex;
            justify-content: space-around;
            /* padding-left: 68px; */
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 23px;
        }

        #item-description {
            display: flex;
            justify-content: space-around;
            /* padding-left: 68px; */
            padding-top: 20px;
            color: rgb(8, 9, 9);
            font-weight: bold;
            font-size: 23px;
        }

        .right-1 {
            margin: 118px 0 50px 100px;
            height: 750px;
            width: 100%;
            background-color: #c3c0c0;
        }

        .right-1 img {
            height: 450px;
            width: 100%;
            border-radius: 0 0 60px 60px;
        }

        hr.solid {
            border-top: 3px solid #bbb;
        }

        @media (max-width: 700px) {
            .right-1 {
                margin: -30px 0 -50px -8px;
                width: 100%;
                padding-right:14px;
            }

            .right-1 img {
                margin:15px 8px ;
                width: 100%;
                height: 440px;
                
            }
         .payment-button {
  background-color:rgb(0, 0, 0);
  color: white;
  border: none;
  padding: 12px 50px;
  font-size: 16px;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  text-align:center;
  margin-left:20px;
}
.text{
    font-weight:300;
}

.payment-button:hover {
  background-color:rgb(96, 94, 94);
  color:black;
  
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
            <hr class="solid">
            <div id="item-price">
                <div>Price:</div>
                <div>₹ <?php echo $itemPrice; ?></div>
            </div>
            <hr class="solid">
          <!--  <div id="item-description"><?php echo $itemDescription; ?></div> -->

          <div class="payment-button">
                <a href="./" class="text">BUY NOW</a>
           </div>

        </div>
    </div>

     


</body>

</html>