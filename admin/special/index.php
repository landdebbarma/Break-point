<?php
include('../includes/header.php');
include('../includes/navbar.php');
include_once '../../config/dbcon.php';
?>

<div class="container1"
    style="
        display: flex;
        justify-content: space-between; 
        background: white; 
        position: relative; 
        width: 100%; 
        margin: 10px auto; 
        border-radius: 30px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); 
        padding: 20px;">
    <h1 class="fs-1 fw-bold tracking-tight">Today's Special</h1>
    <button type="button" class="btn btn-primary"
        style=" 
            background: cornflowerblue;
            padding: 10px 15px;
            color: black;
            font-weight: bolder;
            font-size: 15px;
            border-radius: 30px;
            text-decoration: none;
            margin: 10px 10px 10px auto;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Create New
    </button>
</div>

<!-- Create Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="itemForm" action="special.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Today's Special</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="item_name" placeholder="">
                        <label for="floatingInput">Item Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="item_price" placeholder="">
                        <label for="floatingInput">Item Price</label>
                    </div>
                    <label for="imageUpload" class="py-2">Upload an image</label>
                    <input type="file" name="image" class="form-control">
                    <div class="form-floating mt-3 mb-3">
                        <textarea class="form-control" aria-label="With textarea" name="item_description" placeholder=""></textarea>
                        <label for="floatingInput">Item Description</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" action="items.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="itemId" id="itemId" value="">
                <input type="hidden" name="current_image" value="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Menu Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="Current Image" style="width: 100%; height: auto;" class="my-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="item_name" placeholder="">
                        <label for="floatingInput">Item Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="item_Price" placeholder="">
                        <label for="floatingInput">Item Price</label>
                    </div>
                    <label for="imageUpload" class="py-2">Upload new image</label>
                    <input type="file" name="image" class="form-control">

                    <div class="input-group mb-3 py-4">
                        <select class="form-select" id="inputGroupSelect01" name="category_name">
                            <option disabled>Select Category</option>
                            <?php
                            foreach ($categories as $category) {
                                $isSelected = $category['categoryName'] === $selectedCategory ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($category['categoryName']) . '" ' . $isSelected . '>' . htmlspecialchars($category['categoryName']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" aria-label="With textarea" name="item_description" placeholder=""></textarea>
                        <label for="floatingInput">Item Description</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="outOfStock">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Out of Stock</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveButton" disabled>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Items Card -->
<?php

try {
    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT itemId, itemName, itemDescription, price, imageUrl FROM menuItems WHERE categoryName = :category");
    $stmt->execute(['category' => "Today's Special"]);

    // Fetch the results
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
?>
        <div class="card m-3" style="max-width: 540px; height: 300px;"> <!-- Adjust height as needed -->
            <div class="row g-0 h-100">
                <div class="col-md-4" style="height: 100%;">
                    <img src="<?php echo htmlspecialchars($item['imageUrl']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($item['itemName']); ?>" style="height: 100%; width: 100%; object-fit: cover;">
                </div>
                <div class="col-md-8 d-flex flex-column" style="padding: 1rem;">
                    <h5 class="card-title" style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['itemName']); ?></h5>
                    <p class="card-text">
                        <?php echo htmlspecialchars($item['itemDescription']); ?>
                    </p>
                    <p class="card-text" style="margin-top: auto;"><small class="text-muted" style="font-weight: bold;">Price: ₹<?php echo htmlspecialchars($item['price']); ?></small></p>
                    <a href="#" class="btn btn-primary edit-button"> Edit </a>
                </div>
            </div>
        </div>
<?php
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



<?php include('../includes/footer.php') ?>

<script>
    // Handle Create
    document.getElementById("itemForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("special.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);

                let modal = bootstrap.Modal.getInstance(document.querySelector("#staticBackdropLabel").closest(".modal"));
                modal.hide();

                document.getElementById("itemForm").reset();

                window.location.reload();
            })
            .catch(error => console.error("Error:", error));
    });

    // Reset form after Cancel
    document.getElementById("cancelButton").addEventListener("click", function() {
        document.getElementById("itemForm").reset();
    });
</script>