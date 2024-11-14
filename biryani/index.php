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
  $stmt->execute(['category' => 'Beverages']);

  $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <style>
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
      <h2>FELL THE TASTE<br>Relish Delights</h2>
    </div>
  </nav>

  <!-- nav -->
  <div class="main-content">
    <section class="container2">
      <div class="section-1-container">
        <div class="section-1-items">
          <a href="../starters/"><img src="../img/www.png"></a>
        </div>
        <div class="section-1-items">
          <a href="../items"><img src="../img/llllll-removebg-preview.png"></a>
        </div>
        <div class="section-1-items">
          <a href="#"> <img src="../img/jjjj-removebg-preview.png"></a>
        </div>
        <div class="section-1-items">
          <a href="../beverages/"><img src="../img/mmmm-removebg-preview.png"></a>
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
    <p>&copy Break point | All rights reserved 2024<br>THIS WEB SITE IS ONLY FOR MOBILE VIEW</p>
  </section>





</body>

</html>

<script src="/break-point/admin/assets/js/jquery-3.7.1.min.js"></script>
<script src="/break-point/admin/assets/js/bootstrap.bundle.min.js"></script>