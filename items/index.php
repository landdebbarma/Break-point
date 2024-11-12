<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <title>BREAK POINT</title>

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../style.css">
    <?php
    include('../config/dbcon.php');

    $sql = "SELECT itemName, price, imageUrl FROM menuItems WHERE categoryName = :category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['category' => 'Starters']);

    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- <style>
        .container1 {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            gap: 20px;
            /* Adds spacing between logo and text */
        }

        .logo {
            flex-shrink: 0;
            /* Prevents the logo from resizing */
        }

        .logo img {
            width: 90px;
            border-radius: 50%;
        }

        .text1 {
            text-align: center;
            /* Ensures text is centered within this div */
        }

        .text1 h2 {
            font-family: "Sofia", sans-serif;
            font-weight: bold;
            color: red;
            margin: 0;
            /* Remove any top margin */
            line-height: 1.2;
            /* Adjust line-height if necessary */
        }
    </style> -->

</head>

<!--navbar top-->

<body>
    <nav>
        <div class="container1">
            <a href="../" class="logo">
                <img src="../img/break point.png" alt="Logo" />
            </a>
        </div>
        <div class="text1">
            <h2>YOUR HUB TO<br>Relish Delights</h2>
        </div>
    </nav>

    <!-- nav -->
    <div class="main-content">
        <section class="container2">
            <div class="section-1-container">
                <div class="section-1-items">
                    <a href="../starters/"><img src="../img/burger.png"></a>
                </div>
                <div class="section-1-items">
                    <a href="#"><img src="../img/701965.png"></a>
                </div>
                <div class="section-1-items">
                    <a href="default.asp"><img src="../img/17868004.png"></a>
                </div>
                <div class="section-1-items">
                    <a href="default.asp"> <img src="../img/3081098.png"></a>
                </div>

            </div>
        </section>


        <!--menu items-->
        <!--product-->

        <section id="container-1">
            <?php foreach ($menuItems as $row): ?>
                <?php
                // Sanitize the image URL
                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                $imageSrc = "../" . htmlspecialchars($imageUrl);
                ?>
                <div class="box">
                    <a href="itemDetail.php?name=<?php echo urlencode($row['itemName']); ?>">
                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($row['itemName']); ?>">
                    </a>
                    <span id="a"><?php echo htmlspecialchars($row['itemName']) . ' ₹' . htmlspecialchars($row['price']); ?></span>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <footer id="footer">
        <div>
            <a href="../contacts/" class="footer-title">address</a>
        </div>
        <div>
            <a href="../contacts/" class="footer-title">contacts</a>
        </div>
        <div>
            <a href="../contacts/" class="footer-title">Developer</a>
        </div>


    </footer>

    <section id="copyright">
        <p>&copy Break point | All rights reserved</p>
    </section>

</body>

</html>