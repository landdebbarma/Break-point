<?php
session_start();

include_once("../../config/dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $category_name = $_POST["category_name"];
    // $user_uuid = $_SESSION['uuid'];
    $categoryId = bin2hex(random_bytes(16));

    $allowedTypes = [
        "image/jpeg", 
        "image/png", 
        "image/jpg", 
        "image/heic",
        "image/heif",   
        "image/svg+xml",
        "image/webp"
    ];    $imageType = $_FILES["image"]["type"];

    if (in_array($imageType, $allowedTypes)) {
        $uploadDir = "../../img/uploads/Category/";
        $imageName = basename($_FILES["image"]["name"]);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            $stmt = $pdo->prepare("INSERT INTO menucategory (categoryId, categoryName, imageUrl) VALUES (:categoryId, :Name, :imageUrl)");
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->bindParam(':Name', $category_name);
            $stmt->bindParam(':imageUrl', $imagePath);
            // $stmt->bindParam(':user_uuid', $user_uuid);
            $stmt->execute();

            echo "category uploaded successfully!";
        } else {
            echo "category upload failed";
        }
    } else {
        echo "Invalid file type. Please upload a JPEG, PNG, SVG, webp or HEIC image.";
    }
}
?>
