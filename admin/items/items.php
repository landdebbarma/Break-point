<?php
session_start();
include_once("../../config/dbcon.php");

// Handle create action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if ($_POST["action"] == "create" && isset($_FILES["image"])) {
        if (isset($_SESSION['uuid'])) {
            $item_name = $_POST["item_name"];
            $category_name = $_POST["category_name"];
            $item_description = $_POST["item_description"];
            $user_uuid = $_SESSION['uuid'];
            $itemId = bin2hex(random_bytes(16));

            $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/heic", "image/heif", "image/svg+xml", "image/webp"];
            $imageType = $_FILES["image"]["type"];

            if (in_array($imageType, $allowedTypes)) {
                $uploadDir = "../../img/uploads/Items/";
                $imageName = basename($_FILES["image"]["name"]);
                $imagePath = $uploadDir . $imageName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    $stmt = $pdo->prepare("INSERT INTO menuItems (itemId, itemName, imageUrl, user_uuid, categoryName, itemDescription) VALUES (:itemId, :Name, :imageUrl, :user_uuid, :categoryName, :item_description)");
                    $stmt->bindParam(':itemId', $itemId);
                    $stmt->bindParam(':Name', $item_name);
                    $stmt->bindParam(':imageUrl', $imagePath);
                    $stmt->bindParam(':user_uuid', $user_uuid);
                    $stmt->bindParam(':categoryName', $category_name);
                    $stmt->bindParam(':item_description', $item_description);
                    $stmt->execute();

                    echo "Item uploaded successfully!";
                } else {
                    echo "Item upload failed.";
                }
            } else {
                echo "Invalid file type. Please upload a JPEG, PNG, SVG, WEBP, or HEIC image.";
            }
        } else {
            echo "User session not found. Please log in again.";
        }
    }

    // Handle delete action
    if ($_POST["action"] == "delete") {
        if (isset($_POST['itemName'])) {
            $itemNameToDelete = $_POST['itemName'];

            try {
                $stmt = $pdo->prepare("DELETE FROM menuItems WHERE itemName = :itemName");
                $stmt->bindParam(':itemName', $itemNameToDelete);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "Item $itemNameToDelete deleted successfully.";
                } else {
                    echo "No item found with the name $itemNameToDelete.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Item name not provided for deletion.";
        }
    }
} else {
    echo "Invalid request method or action.";
}
