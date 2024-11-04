<?php
session_start();

include_once("../../config/dbcon.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    // Handle create action
    if ($_POST["action"] == "create" && isset($_FILES["image"])) {
        $billboard_name = $_POST["billboard_name"];
        $user_uuid = $_SESSION['uuid'];

        $allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/jpg",
            "image/heic",
            "image/heif",
            "image/svg+xml",
            "image/webp"
        ];
        $imageType = $_FILES["image"]["type"];

        if (in_array($imageType, $allowedTypes)) {
            $uploadDir = "../../img/uploads/Billboard/";
            $imageName = basename($_FILES["image"]["name"]);
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $stmt = $pdo->prepare("INSERT INTO billboards (billboardName, imageUrl, user_uuid) VALUES (:Name, :imageUrl, :user_uuid)");
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

    // Handle delete action
    if ($_POST["action"] == "delete") {
        if (isset($_POST['billboardName'])) {
            $billboardNameToDelete = $_POST['billboardName'];

            try {
                $stmt = $pdo->prepare("SELECT imageUrl FROM billboards WHERE billboardName = :billboardName");
                $stmt->bindParam(':billboardName', $billboardNameToDelete);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && !empty($result['imageUrl'])) {
                    $imageUrl = $result['imageUrl'];

                    if (file_exists($imageUrl)) {
                        unlink($imageUrl);
                    }
                }

                $stmt = $pdo->prepare("DELETE FROM billboards WHERE billboardName = :billboardName");
                $stmt->bindParam(':billboardName', $billboardNameToDelete);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "Billboard $billboardNameToDelete deleted successfully.";
                } else {
                    echo "No billboard found with the name $billboardNameToDelete.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Billboard name not provided for deletion.";
        }
    }
} else {
    echo "Invalid request method or action.";
}
?>
