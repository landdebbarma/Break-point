<?php
session_start();

include_once("../config/dbcon.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    // Handle delete billboard action
    if ($_POST["action"] == "deleteBillboard") {
        if (isset($_POST['billboardName'])) {
            $billboardNameToDelete = $_POST['billboardName'];

            try {
                // Fetch the image path associated with the billboard from the database
                $stmt = $pdo->prepare("SELECT imageUrl FROM billboards WHERE billboardName = :billboardName");
                $stmt->bindParam(':billboardName', $billboardNameToDelete);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if an image path was found
                if ($result && !empty($result['imageUrl'])) {
                    $imageUrl = $result['imageUrl'];

                    // Attempt to delete the image file if it exists
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

    // Handle delete item action
    if ($_POST["action"] == "deleteItem") {
        if (isset($_POST['itemName'])) {
            $itemNameToDelete = $_POST['itemName'];

            try {
                // Fetch the image path associated with the item from the database
                $stmt = $pdo->prepare("SELECT imageUrl FROM menuItems WHERE itemName = :itemName");
                $stmt->bindParam(':itemName', $itemNameToDelete);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if an image path was found
                if ($result && !empty($result['imageUrl'])) {
                    $imageUrl = $result['imageUrl'];

                    // Attempt to delete the image file if it exists
                    if (file_exists($imageUrl)) {
                        unlink($imageUrl);
                    }
                }

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
?>
