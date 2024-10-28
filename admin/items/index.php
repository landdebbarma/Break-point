<?php
include('../includes/header.php');
include('../includes/navbar.php');
include_once '../../config/dbcon.php';

try {
    $query = "SELECT id, categoryName FROM menuCategory ORDER BY id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
}
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
    <h1 class="fs-1 fw-bold tracking-tight">Menu Items</h1>
    <button type="button" class="btn btn-primary"

        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Create New
    </button>
</div>

<!-- Create Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="itemForm" action="items.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Menu Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="item_name" placeholder="">
                        <label for="floatingInput">Item Name</label>
                    </div>
                    <label for="imageUpload" class="py-2">Upload an image</label>
                    <input type="file" name="image" class="form-control">
                    <div class="input-group mb-3 py-4">
                        <select class="form-select" id="inputGroupSelect01" name="category_name">
                            <option selected disabled>Select Category</option>
                            <?php
                            foreach ($categories as $category) {
                                echo '<option value="' . htmlspecialchars($category['categoryName']) . '">' . htmlspecialchars($category['categoryName']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-floating mb-3">
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
                <input type="hidden" name="action" value="edit">
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
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="out_of_stock">
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" action="items.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="itemName" id="deleteItemName" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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

<!-- Items Card -->
<div class="container my-5">
    <div class="row">
        <?php
        try {
            $stmt = $pdo->query("SELECT itemName, itemDescription, imageUrl, categoryName, inStock FROM menuitems");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                $itemName = htmlspecialchars($row['itemName']);
                $itemDescription = htmlspecialchars($row['itemDescription']);
                $imageSrc = "../../" . htmlspecialchars($imageUrl);
                $categoryName = htmlspecialchars($row['categoryName']);
                $inStock = htmlspecialchars($row['inStock']);

                echo <<<HTML
                <div class="col-md-4 mb-4">
                    <div class="card" style="width: 18rem; height: 27rem;">
                        <img src="$imageSrc" style="height: 18rem; object-fit: cover; object-position: center;" class="card-img-top" alt="$itemName">
                        <div class="card-body">
                            <h5 class="card-title">$itemName</h5>
                            <p class="card-text text-truncate">$itemDescription</p>
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-primary edit-button" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-name="$itemName"
                                data-description="$itemDescription"
                                data-image="$imageSrc"
                                data-category="{$row['categoryName']}"
                                data-stock="{$row['inStock']}"
                                >
                                Edit
                                </a>
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
                HTML;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>
</div>

<?php include('../includes/footer.php') ?>

<script src="script.js"></script>