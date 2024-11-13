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
            <form id="editForm" action="special.php" method="POST" enctype="multipart/form-data">
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

                    <div class="form-floating mb-3 mt-3">
                        <textarea class="form-control" aria-label="With textarea" name="item_description" placeholder=""></textarea>
                        <label for="floatingInput">Item Description</label>
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

        $stmt = $pdo->prepare("SELECT itemId, itemName, itemDescription, price, imageUrl FROM menuItems WHERE categoryName = :category");
        $stmt->execute(['category' => "Special"]);

        while ($items = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $items['imageUrl']);
            $itemName = htmlspecialchars($items['itemName']);
            $itemDescription = htmlspecialchars($items['itemDescription']);
            $imageSrc = "../../" . htmlspecialchars($imageUrl);
            $itemId = htmlspecialchars($items['itemId']);
            $itemPrice = htmlspecialchars($items['price']);


            echo <<<HTML
                <div class="card m-3" style="max-width: 540px; height: 300px;">
                    <div class="row g-0 h-100">
                        <div class="col-md-4" style="height: 100%;">
                            <img src="$imageSrc" class="img-fluid rounded-start" alt="" style="height: 100%; width: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-8 d-flex flex-column" style="padding: 1rem;">
                            <h5 class="card-title" style="margin-bottom: 0.5rem;">$itemName</h5>
                            <p class="card-text">
                                $itemDescription
                            </p>
                            <div class="d-flex justify-content-between mt-auto">
                                <p class="card-text"><small class="text-muted" style="font-weight: bold;">Price: ₹ $itemPrice</small></p>
                                <a href="#" 
                                            class="btn btn-primary edit-button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-id="{$items['itemId']}"
                                            data-name="$itemName"
                                            data-price="$itemPrice"
                                            data-image="$imageSrc"
                                            data-description="$itemDescription"
                                        >
                                        Edit
                                        </a>
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

    // Handle update
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById("editModal");
        let initialData = {};

        editModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const itemId = button.getAttribute("data-id");


            initialData = {
                itemId,
                itemName: button.getAttribute("data-name"),
                itemPrice: button.getAttribute("data-price"),
                itemDescription: button.getAttribute("data-description"),
                imageUrl: button.getAttribute("data-image"),
            };
            console.log(initialData);

            editModal.querySelector('input[name="item_name"]').value = initialData.itemName;
            editModal.querySelector('input[name="item_Price"]').value = initialData.itemPrice;
            editModal.querySelector('textarea[name="item_description"]').value = initialData.itemDescription;
            editModal.querySelector(".modal-body img").src = initialData.imageUrl;
            editModal.querySelector(".modal-title").textContent = `Edit Menu Item: ${initialData.itemName}`;
            editModal.querySelector('input[name="itemId"]').value = initialData.itemId;
        });

        document.getElementById("editForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const formData = new FormData();

            const fields = [{
                    name: 'item_name',
                    value: editModal.querySelector('input[name="item_name"]').value
                },
                {
                    name: 'item_Price',
                    value: editModal.querySelector('input[name="item_Price"]').value
                },
                {
                    name: 'item_description',
                    value: editModal.querySelector('textarea[name="item_description"]').value
                },
                {
                    name: 'image',
                    value: editModal.querySelector('input[type="file"]').files[0]
                }
            ];

            fields.forEach(field => {
                if (field.value !== initialData[field.name]) {
                    formData.append(field.name, field.value);
                }
            });

            formData.append("itemId", initialData.itemId);
            formData.append("action", "update");

            fetch("special.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    bootstrap.Modal.getInstance(editModal).hide();
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        });
    });
    
    // Turn off disabled Save button if any changes are made
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("editForm");
        const saveButton = document.getElementById("saveButton");
        const initialFormState = new FormData(form);

        form.addEventListener("input", function() {
            const currentFormState = new FormData(form);
            let isChanged = false;

            for (let [key, value] of currentFormState.entries()) {
                if (value !== initialFormState.get(key)) {
                    isChanged = true;
                    break;
                }
            }

            saveButton.disabled = !isChanged;
        });
    });
</script>