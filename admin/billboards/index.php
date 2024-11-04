<?php
include('../includes/header.php');
include('../includes/navbar.php');
include_once '../../config/dbcon.php';
?>

<div class="container1"
    style="
        display: flex; 
        background: white; 
        position: relative; 
        width: 100%; 
        margin: 10px auto; 
        border-radius: 30px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); 
        padding: 20px;">
    <h1 class="fs-1 fw-bold tracking-tight">Billboards</h1>
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
            <form id="billboardForm" action="billboard.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Billboard</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="billboard_name" placeholder="" required>
                        <label for="floatingInput">Billboard Name</label>
                    </div>
                    <label for="imageUpload" class="py-2">Upload an image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" action="billboard.php" method="POST">
                <input type="hidden" name="action" value="delete">
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

<!-- Billboards Card -->
<div class="container my-5">
    <div class="row">
        <?php
        try {
            $stmt = $pdo->query("SELECT billboardName, imageUrl FROM billboards");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imageUrl = preg_replace('/^\.\.\/\.\.\//', '', $row['imageUrl']);
                $billboardName = htmlspecialchars($row['billboardName']);
                $imageSrc = "../../" . htmlspecialchars($imageUrl);
                // $billboardId = htmlspecialchars($row['billboardId']);

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
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

    </div>
</div>

<?php include('../includes/footer.php') ?>

<script>
    // Handle Create
    document.getElementById("billboardForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("billboard.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);

                let modal = bootstrap.Modal.getInstance(document.querySelector("#staticBackdropLabel").closest(".modal"));
                modal.hide();

                document.getElementById("billboardForm").reset();

                window.location.reload();
            })
            .catch(error => console.error("Error:", error));
    });

    // Reset form after Cancel
    document.getElementById("cancelButton").addEventListener("click", function() {
        document.getElementById("billboardForm").reset();
    });

    // Fetch name for delete
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
    
    // Handle delete
    document.getElementById("deleteForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("billboard.php", {
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