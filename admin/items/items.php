<?php
session_start();
include_once("../../config/dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    if (isset($_SESSION['uuid'])) {
        $item_name = $_POST["item_name"];
        $category_name = $_POST["category_name"]; // Get the category name from the form
        $user_uuid = $_SESSION['uuid'];
        $itemId = bin2hex(random_bytes(16));

        $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/heic", "image/heif", "image/svg+xml", "image/webp"];
        $imageType = $_FILES["image"]["type"];

        if (in_array($imageType, $allowedTypes)) {
            $uploadDir = "../../img/uploads/Items/";
            $imageName = basename($_FILES["image"]["name"]);
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                // Insert the item into the menuItems table using categoryName
                $stmt = $pdo->prepare("INSERT INTO menuItems (itemId, itemName, imageUrl, user_uuid, categoryName) VALUES (:itemId, :Name, :imageUrl, :user_uuid, :categoryName)");
                $stmt->bindParam(':itemId', $itemId);
                $stmt->bindParam(':Name', $item_name);
                $stmt->bindParam(':imageUrl', $imagePath);
                $stmt->bindParam(':user_uuid', $user_uuid);
                $stmt->bindParam(':categoryName', $category_name); // Bind categoryName here
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
?>
