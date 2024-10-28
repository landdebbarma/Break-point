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

                echo '<div class="col-md-4 mb-4">';
                echo '    <div class="card" style="width: 18rem;">';
                echo '        <img src="../' . htmlspecialchars($imageUrl) . '" class="card-img-top" alt="' . htmlspecialchars($row['itemName']) . '">';
                echo '        <div class="card-body">';
                echo '            <h5 class="card-title">' . htmlspecialchars($row['itemName']) . '</h5>';
                echo '            <p class="card-text">' . htmlspecialchars($row['itemDescription']) . '</p>';
                echo '            <a href="#" class="btn btn-primary">Edit</a>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>
</div>
<!-- <div class="card" style="width: 18rem;">
    <img src="../img/uploads/Items/coke.webp" class="card-img-top">
    <div class="card-body">
        <h5 class="card-title">Coke</h5>
        <p class="card-text">Some quick refreshment.</p>
        <a href="#" class="btn btn-primary">Edit</a>
    </div>
</div> -->

<?php include('includes/footer.php') ?>