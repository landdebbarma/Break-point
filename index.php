<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, intial-scale=1.0" />
  <title>BREAK POINT</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" href="/favicon.ico" type="image/png">

  <?php
  include '/xampp/htdocs/Break-point/config/dbcon.php';

  // Fetch image URLs from the billboards table
  try {
    $query = "SELECT imageUrl FROM billboards";
    $stmt = $pdo->query($query);
    $images = [];

    // Process each URL
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
      $imageSrc = "" . htmlspecialchars($imageUrl);
      $images[] = $imageSrc;
    }
    // echo "<pre>";
    // print_r($images); // This will print all image URLs fetched and processed
    // echo "</pre>";
  } catch (PDOException $e) {
    echo "Error fetching images: " . $e->getMessage();
    $images = []; // Set empty array if an error occurs
  }
  ?>


  <style>
    /*sliding offer*/
    .section-2-container {
      margin-top: 200px;
      width: 100%;
      height: 220px;
      object-fit: cover;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      animation-name: slide;
      animation-duration: 10s;
      animation-iteration-count: infinite;
    }

    @keyframes slide {
      <?php
      // Loop through images to create each keyframe
      if (!empty($images)) {
        $numImages = count($images);
        $percentageIncrement = 100 / $numImages; // Evenly distribute keyframe percentages

        foreach ($images as $index => $imageUrl) {
          $percentage = $index * $percentageIncrement;
          echo "{$percentage}% { background-image: url('$imageUrl'); }\n";
        }
        echo "100% { background-image: url('" . $images[0] . "'); }\n"; // Loop back to the first image
      } else {
        echo "0% { background-image: url('default_image.png'); }\n"; // Fallback if no images
      }
      ?>
    }
  </style>

</head>

<!--navbar top-->

<body>
  <nav>
    <div class="container1">
      <div class="left">
        <div class="logo">
          <a href="">
            <img src="img/break point.jpg" />
          </a>
        </div>
        <div class="text1">
          <h2>YOUR HUB TO<br />Relish Delights</h2>
        </div>
      </div>
    </div>
  </nav>

  <!--menu items-->

  <section class="container2">
    <div class="section-1-container">
      <div class="section-1-items">
        <a href="startrs.html"><img src="img/burger.png" /></a>
      </div>
      <div class="section-1-items">
        <a href="main-course.html"><img src="img/701965.png" /></a>
      </div>
      <div class="section-1-items">
        <a href="default.asp"><img src="img/17868004.png" /></a>
      </div>
      <div class="section-1-items">
        <a href="default.asp"> <img src="img/3081098.png" /></a>
      </div>
    </div>
  </section>

  <!--sliding offer-->

  <section class="container3">
    <section class="section-2-container">
      <div class="section-2-container"></div>
    </section>
  </section>

  <!--today's special-->

  <section id="add-offer">
    <div class="container5">
      <h2>Todays Special</h2>
      <div class="todays-special">
        <img src="img/tandoori-momo.webp" alt="" />
        <img src="img/tandoori-momo.webp" alt="" />
        <img src="img/tandoori-momo.webp" alt="" />
        <img src="img/tandoori-momo.webp" alt="" />
      </div>
    </div>
  </section>

  <!--about-us-->

  <section id="about">
    <div class="container4">
      <div class="left">
        <h2>Discover The <span>BREAK POINT Taste</span></h2>
      </div>

      <div class="right">
        <p>
          <strong>Welcome</strong> To Break Point.<br /> We Offer Good Quality
          of Food And Beverage . Along with food you can enjoy calm senario and
          our restaurant build is enhanced wiith fatched root and bamboo
          bench.A beauty mixed with culture.
        </p>
      </div>
    </div>
  </section>

  <!--terms & condition-->

  <section id="footer">
    <div>
      <h3 class="footer-title">support</h3>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
    </div>
    <div>
      <h3 class="footer-title">contacts</h3>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
    </div>
    <div>
      <h3 class="footer-title">privacy</h3>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
      <a href="" class="footer-items">
        <i class="fa-solid fa-chevron-right"></i>
        service</a>
    </div>
  </section>
  <section id="copyright">
    <p>&copy Break point | All right recived</p>
  </section>
</body>

</html>