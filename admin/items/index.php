<?php
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

<?php include('../includes/header.php') ?>
<?php include('../includes/navbar.php') ?>

<div class="container1"
    style="
        display: flex; 
        background: white; 
        position: absolute; 
        width: 100%; 
        margin: 10px auto; 
        border-radius: 30px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); 
        padding: 20px;">
    <h1 class="fs-1 fw-bold tracking-tight">Menu Items</h1>
    <button type="button" class="btn btn-primary"
        style=" 
            background: cornflowerblue;
            padding: 10px 15px;
            color: black;
            font-weight: bolder;
            font-size: 15px;
            border-radius: 30px;
            text-decoration: none;
            margin: 10px 10px 10px auto;"
        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Create New
    </button>
</div>


<div class="modal fade modal-dialog modal-dialog-centered" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="itemForm" action="items.php" method="POST" enctype="multipart/form-data">
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

<?php include('../includes/footer.php') ?>

<script>
    document.getElementById("itemForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("items.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);

                let modal = bootstrap.Modal.getInstance(document.querySelector("#staticBackdropLabel").closest(".modal"));
                modal.hide();

                document.getElementById("itemForm").reset();
            })
            .catch(error => console.error("Error:", error));
    });

    document.getElementById("cancelButton").addEventListener("click", function() {
        document.getElementById("itemForm").reset();
    });
</script>