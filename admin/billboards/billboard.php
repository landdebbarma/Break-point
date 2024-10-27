<?php
session_start();

include_once("../../config/dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $billboard_name = $_POST["billboard_name"];
    $user_uuid = $_SESSION['uuid'];
    $billboardId = bin2hex(random_bytes(16));

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
        $uploadDir = "../../img/uploads/Billboard/";
        $imageName = basename($_FILES["image"]["name"]);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            $stmt = $pdo->prepare("INSERT INTO billboards (billboardId, billboardName, imageUrl, user_uuid) VALUES (:billboardId, :Name, :imageUrl, :user_uuid)");
            $stmt->bindParam(':billboardId', $billboardId);
            $stmt->bindParam(':Name', $billboard_name);
            $stmt->bindParam(':imageUrl', $imagePath);
            $stmt->bindParam(':user_uuid', $user_uuid);
            $stmt->execute();

            echo "Billboard uploaded successfully!";
        } else {
            echo "Billboard upload failed";
        }
    } else {
        echo "Invalid file type. Please upload a JPEG, PNG, SVG, webp or HEIC image.";
    }
}
?>
