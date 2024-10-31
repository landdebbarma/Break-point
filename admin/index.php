<?php
include('includes/header.php');
include('includes/navbar.php');
include_once '../config/dbcon.php';

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header("Location: login");
    exit;
}

try {
    $query = "SELECT id, categoryName FROM menuCategory ORDER BY id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
}

// Handle search request
$billboardResults = [];
$itemResults = [];

// Handle search request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['search'])) {
    $searchTerm = $_POST['search'] ?? '';
    $searchTerm = "%$searchTerm%"; // Prepare for LIKE clause

    // Search in billboards
    try {
        $stmt = $pdo->prepare("SELECT billboardId, billboardName, imageUrl FROM billboards WHERE billboardName LIKE :searchTerm");
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $billboardResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Search in menu items
    try {
        $stmt = $pdo->prepare("SELECT itemId, itemName, itemDescription, imageUrl, price, outOfStock FROM menuItems WHERE itemName LIKE :searchTerm");
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $itemResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Fetch all billboards and items if no search term is provided
    try {
        $stmt = $pdo->query("SELECT billboardId, billboardName, imageUrl FROM billboards");
        $billboardResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    try {
        $stmt = $pdo->query("SELECT itemId, itemName, itemDescription, imageUrl, price, outOfStock FROM menuItems");
        $itemResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>


<div class="container1"
    style="
        display: flex; 
        background: white; 
        position: relative; 
        width: 100%; 
        margin: auto; 
         
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); 
        padding: 20px;
        justify-content: space-between;
        align-items: center;">

    <!-- Left-aligned header -->
    <div class="left-content">
        <h1 class="fs-1 fw-bold tracking-tight">Dashboard</h1>
    </div>

    <!-- Right-aligned elements -->
    <div class="right-content d-flex align-items-center">
        <div class="dropdown me-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by category
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <?php
                foreach ($categories as $category) {
                    echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($category['categoryName']) . '">' . htmlspecialchars($category['categoryName']) . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <div class="dropdown me-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by time
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <li><a class="dropdown-item" href="#">New</a></li>
                <li><a class="dropdown-item" href="#">Old</a></li>
            </ul>
        </div>

        <div class="dropdown me-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by price
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <li><a class="dropdown-item" href="#">Low to High</a></li>
                <li><a class="dropdown-item" href="#">High to Low</a></li>
            </ul>
        </div>

        <form class="d-flex" method="POST" action="">
            <input class="form-control me-2" type="text" name="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</div>

<!-- Billboards Card -->
<div>
    <h2 class="p-2 ">Billboards</h2>
    <hr class="hr" />
    <div class="container my-2">
        <div class="row">
            <?php
            // Check if there are results to display
            if (count($billboardResults) > 0) {
                foreach ($billboardResults as $row) {
                    $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                    $billboardName = htmlspecialchars($row['billboardName']);
                    $imageSrc = "../" . htmlspecialchars($imageUrl);
                    $billboardId = htmlspecialchars($row['billboardId']);

                    echo <<<HTML
                    <div class="col-md-6 mb-4">
                        <div class="card" style="width: 35rem; height: 22rem;">
                            <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$billboardName">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title text-truncate flex-grow-1">$billboardName</h5>
                                    <button class="btn btn-danger delete-button mb-0 ms-3 flex-shrink-0" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-billboard-name="$billboardName"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    HTML;
                }
            } else {
                echo "<p>No results found in Billboards.</p>";
            }
            ?>

        </div>
    </div>
</div>

<!-- Items Card -->
<div>
    <h2 class="p-2 ">Items</h2>
    <hr class="hr" />
    <div class="container my-5">
        <div class="row">
        <?php
            if (count($itemResults) > 0) {
                foreach ($itemResults as $row) {
                    $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                    $itemName = htmlspecialchars($row['itemName']);
                    $itemDescription = htmlspecialchars($row['itemDescription']);
                    $imageSrc = "../" . htmlspecialchars($imageUrl);
                    $itemId = htmlspecialchars($row['itemId']);
                    $itemPrice = htmlspecialchars($row['price']);
                    $outOfStock = htmlspecialchars($row['outOfStock']);

                    $outOfStockLabel = $outOfStock == 1 ? "<label class='card-title mb-0 ms-3 flex-shrink-0'><strong>Out Of Stock</strong></label>" : "";

                    echo <<<HTML
                    <div class="col-md-4 mb-4">
                        <div class="card" style="width: 18rem; height: 27rem;">
                            <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$itemName">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title text-truncate flex-grow-1">$itemName</h5>
                                    $outOfStockLabel
                                </div>
                                <p class="card-text text-truncate">$itemDescription</p>
                                <div class="d-flex justify-content-between">
                                    <label for="price"><strong>Price: ₹ $itemPrice</strong></label>
                                    <div>
                                        <a href="#" 
                                            class="btn btn-primary edit-button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                        >
                                        Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    HTML;
                }
            } else {
                echo "<p>No results found in items.</p>";
            }
            ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>