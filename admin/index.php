<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header("Location: login");
    exit;
}
?>

<?php include('includes/header.php') ?>
<?php include('includes/navbar.php') ?>
<h1>Hello, Admin anna!</h1>
<div class="container my-5">
    <div class="row">
        <?php
        include '../config/dbcon.php';

        try {
            $stmt = $pdo->query("SELECT itemName, itemDescription, imageUrl FROM menuitems");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                $itemName = htmlspecialchars($row['itemName']);
                $itemDescription = htmlspecialchars($row['itemDescription']);
                $imageSrc = "../" . htmlspecialchars($imageUrl);

                echo <<<HTML
                <div class="col-md-4 mb-4">
                    <div class="card" style="width: 18rem; height: 27rem;">
                        <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$itemName">
                        <div class="card-body">
                            <h5 class="card-title">$itemName</h5>
                            <p class="card-text text-truncate">$itemDescription</p>
                            <div class="d-flex align-items-center">
                                <a href="#" class="btn btn-primary me-auto">Edit</a>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Out of Stock</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                HTML;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>
</div>

<?php include('includes/footer.php') ?>