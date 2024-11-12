<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
include_once '../config/dbcon.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header("Location: login");
    exit;
}

// fetch categories
try {
    $query = "SELECT categoryName FROM menuCategory";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
}

// Handle search request
$billboardResults = [];
$itemResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);

    if ($searchTerm !== '') {
        $searchTerm = "%$searchTerm%";

        $stmt = $pdo->prepare("SELECT , billboardName, imageUrl FROM billboards WHERE billboardName LIKE :searchTerm");
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $billboardResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT itemId, itemName, itemDescription, imageUrl, price, outOfStock FROM menuItems WHERE itemName LIKE :searchTerm");
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $itemResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT , billboardName, imageUrl FROM billboards");
        $billboardResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT itemId, itemName, itemDescription, imageUrl, price, outOfStock FROM menuItems");
        $itemResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $stmt = $pdo->query("SELECT billboardName, imageUrl FROM billboards");
    $billboardResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT itemId, itemName, itemDescription, imageUrl, price, outOfStock FROM menuItems");
    $itemResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Secondary nav -->
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

    <div class="left-content">
        <h1 class="fs-1 fw-bold tracking-tight">Dashboard</h1>
    </div>

    <div class="right-content d-flex align-items-center">
        <form class="d-flex" method="POST">
            <input type="hidden" name="action" value="search">
            <input class="form-control me-2" type="text" name="search" id="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</div>

<!-- Delete Billboard Modal -->
<div class="modal fade" id="deleteModalBillboard" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteFormBillboard" action="dash.php" method="POST">
                <input type="hidden" name="action" value="deleteBillboard">
                <input type="hidden" name="billboardName" id="deletebillboardName" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-truncate">
                    Are you sure you want to delete <strong id="billboardToDelete"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Item Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" action="dash.php" method="POST">
                <input type="hidden" name="action" value="deleteItem">
                <input type="hidden" name="itemName" id="deleteItemName" value="">
                <input type="hidden" name="itemId" id="deleteitemId" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-truncate">
                    Are you sure you want to delete <strong id="itemToDelete"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- accordion -->
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h2>Billboards</h2>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <!-- billboard -->
                <div class="container my-2">
                    <div class="row">
                        <?php
                        if (!empty($billboardResults)) {
                            foreach ($billboardResults as $row) {
                                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                                $billboardName = htmlspecialchars($row['billboardName']);
                                $imageSrc = "../" . htmlspecialchars($imageUrl);
                                echo <<<HTML
                                    <div class="col-md-6 mb-4">
                                        <div class="card" style="width: 35rem; height: 22rem;">
                                            <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$billboardName">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title text-truncate flex-grow-1">$billboardName</h5>
                                                    <button class="btn btn-danger delete-button mb-0 ms-3 flex-shrink-0" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModalBillboard" 
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
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <h2>Menu Items</h2>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <!-- menuItems -->
                <div class="container my-5">
                    <div class="row">
                        <?php
                        if (!empty($itemResults)) {
                            foreach ($itemResults as $item) {
                                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $item['imageUrl']);
                                $itemName = htmlspecialchars($item['itemName']);
                                $imageSrc = "../" . htmlspecialchars($imageUrl);
                                $itemId = htmlspecialchars($item['itemId']);
                                $itemDescription = htmlspecialchars($item['itemDescription']);
                                $itemPrice = htmlspecialchars(number_format($item['price'], 2));
                                $outOfStock = $item['outOfStock'] ? 'Out of Stock' : 'In Stock';

                                echo <<<HTML
                                    <div class="col-md-4 mb-4">
                                        <div class="card" style="width: 18rem; height: 27rem;">
                                            <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$itemName">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title text-truncate flex-grow-1">$itemName</h5>
                                                </div>
                                                <p class="card-text text-truncate">$itemDescription</p>
                                                <div class="d-flex justify-content-between">
                                                    <label for="price"><strong>Price: ₹ $itemPrice</strong></label>
                                                    <div>
                                                        <button class="btn btn-danger delete-button" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal" 
                                                            data-item-name="$itemName"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                HTML;
                            }
                        } else {
                            echo "<p>No results found in Menu Items.</p>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<script>
    // Handle delete Billboard 
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-button");
        const billboardToDelete = document.getElementById("billboardToDelete");
        const deletebillboardName = document.getElementById("deletebillboardName");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function() {
                const billboardName = this.getAttribute("data-billboard-name");
                billboardToDelete.textContent = billboardName;
                deletebillboardName.value = billboardName;
            });
        });
    });

    
    document.getElementById("deleteFormBillboard").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("dash.php", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.text())
            .then((data) => {
                alert(data);

                let modal = bootstrap.Modal.getInstance(document.getElementById('deleteModalBillboard'));
                modal.hide();

                window.location.reload();
            })
            .catch((error) => console.error("Error:", error));
    });

    // Handle delete item 
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-button");
        const itemToDelete = document.getElementById("itemToDelete");
        const deleteItemName = document.getElementById("deleteItemName");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function() {
                const itemName = this.getAttribute("data-item-name");
                itemToDelete.textContent = itemName;
                deleteItemName.value = itemName;
            });
        });
    });

    
    document.getElementById("deleteForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("dash.php", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.text())
            .then((data) => {
                alert(data);

                let modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                modal.hide();

                window.location.reload();
            })
            .catch((error) => console.error("Error:", error));
    });
</script>