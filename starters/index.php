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
    include ('../config/dbcon.php');

    $sql = "SELECT itemName, price, imageUrl FROM menuItems WHERE categoryName = :category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['category' => 'Starters']);

    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

</head>

<!--navbar top-->

<body>
    <div class="wrapper">
        <nav>
            <div class="container1">
                <div class="logo">
                    <a href="/break-point/">
                        <img src="../img/break point.jpg">
                    </a>
                </div>
                <div class="text1">
                    <h2>YOUR HUB TO<br>Relish Delights</h2>
                </div>
            </div>
        </nav>

        <!-- nav -->
        <div class="main-content">
            <section class="container2">
                <div class="section-1-container">
                    <div class="section-1-items">
                        <a href="#"><img src="../img/burger.png"></a>
                    </div>
                    <div class="section-1-items">
                        <a href="../main-course.html"><img src="../img/701965.png"></a>
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
    </div>

    <footer id="footer">
        <div>
            <h3 class="footer-title">support</h3>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                feedback</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                phone</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                email</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                dd</a>
        </div>
        <div>
            <h3 class="footer-title">contacts</h3>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Email</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Instagram</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                whatsapp</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                phone</a>
        </div>
        <div>

            <h3 class="footer-title">Developer</h3>
            <a href="https://github.com/Alam-Monir" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Monir</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Instagram</a>
            <a href="https://github.com/landdebbarma" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Land</a>
            <a href="" class="footer-items">
                <i class="fa-solid fa-chevron-right"></i>
                Instagram</a>
        </div>


    </footer>

    <section id="copyright">
        <p>&copy Break point | All rights reserved</p>
    </section>





</body>

</html>