<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <title>BREAK POINT</title>

    <link rel="stylesheet" href="/break-point/admin/assets/css/bootstrap.min.css">
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../style.css">
    <?php
    include('../config/dbcon.php');

    $sql = "SELECT itemName, price, imageUrl, outOfStock FROM menuItems WHERE categoryName = :category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['category' => 'Starters']);

    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <style>
        .accordion {
            margin-top: 70px;
        }

        #container-1 {
            margin-top: 160px;
        }

        .accordion-body {
            padding: 0;
        }

        .out-of-stock {
            filter: grayscale(100%);
        }

        .out-of-stock-label {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            z-index: 10;
            white-space: nowrap;
            max-width: 90%;
            text-overflow: ellipsis;
        }
    </style>
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

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Accordion Item #1
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <section id="container-1">
                            <?php foreach ($menuItems as $row): ?>
                                <?php
                                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                                $imageSrc = "../" . htmlspecialchars($imageUrl);
                                $isOutOfStock = $row['outOfStock'];
                                ?>
                                <div class="box">
                                    <?php if ($isOutOfStock): ?>
                                        <span class="out-of-stock-label">Out of Stock</span>
                                    <?php endif; ?>
                                    <a href="itemDetail.php?name=<?php echo urlencode($row['itemName']); ?>">
                                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($row['itemName']); ?>"
                                            class="<?php echo $isOutOfStock ? 'out-of-stock' : ''; ?>">
                                    </a>
                                    <div class="d-flex justify-content-evenly p-3">
                                        <div class="text-truncate fw-bold" style="max-width: calc(100% - 50px);"><?php echo htmlspecialchars($row['itemName']) ?></div>
                                        <div class="fw-bold mb-0 ms-3 flex-shrink-0"><?php echo ' ₹' .  htmlspecialchars($row['price']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Accordion Item #2
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
        </div>

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
<script src="/break-point/admin/assets/js/jquery-3.7.1.min.js"></script>
<script src="/break-point/admin/assets/js/bootstrap.bundle.min.js"></script>