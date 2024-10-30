<?php
session_start();
include_once("../../config/dbcon.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    // Handle create action
    if ($_POST["action"] == "create" && isset($_FILES["image"])) {
        if (isset($_SESSION['uuid'])) {
            $item_name = $_POST["item_name"];
            $item_price = $_POST["item_price"];
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
                    $stmt = $pdo->prepare("INSERT INTO menuItems (itemId, itemName, price, imageUrl, user_uuid, categoryName, itemDescription) VALUES (:itemId, :Name, :price, :imageUrl, :user_uuid, :categoryName, :item_description)");
                    $stmt->bindParam(':itemId', $itemId);
                    $stmt->bindParam(':Name', $item_name);
                    $stmt->bindParam(':price', $item_price);
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

    // Handle update action
    elseif ($_POST["action"] === "update" && isset($_POST["itemId"])) {
        $itemId = $_POST["itemId"];
        if (empty($itemId)) {
            echo "Error: itemId is empty.";
            exit;
        }
        $newData = [
            "itemName" => $_POST["item_name"],
            "price" => $_POST["item_Price"],
            "itemDescription" => $_POST["item_description"],
            "categoryName" => $_POST["category_name"],
            "outOfStock" => $_POST["outOfStock"]
        ];

        // Fetch existing data for comparison
        $query = $pdo->prepare("SELECT * FROM menuitems WHERE itemId = :itemId");
        if (!$query->execute([":itemId" => $itemId])) {
            // Output error message if the execution fails
            echo "Query execution failed: " . implode(", ", $query->errorInfo());
            exit; // Stop execution
        }
        $existingData = $query->fetch(PDO::FETCH_ASSOC);

        if ($existingData === false) {
            echo "Error: Item not found or query failed.";
            exit; // Stop execution if the item is not found
        }

        // Track changed fields
        $updateFields = [];
        $params = [":itemId" => $itemId];

        // Ensure that the keys you're accessing exist in the $existingData
        $validKeys = ['itemName', 'price', 'itemDescription', 'categoryName', 'outOfStock'];
        foreach ($validKeys as $key) {
            if (!array_key_exists($key, $existingData)) {
                echo "Error: Missing expected data for key '$key'.";
                exit;
            }
        }

        foreach ($newData as $key => $value) {
            if ($value != $existingData[$key]) {
                $updateFields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        // Handle image upload if a new image is provided
        if (!empty($_FILES["image"]["name"])) {
            $uploadDir = "../../img/uploads/Items/";
            $imageName = basename($_FILES["image"]["name"]);
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                $updateFields[] = "imageUrl = :imageUrl";
                $params[":imageUrl"] = $imagePath;
            } else {
                echo "Image upload failed.";
                exit;
            }
        }

        // Run update if there are changes
        if ($updateFields) {
            $stmt = $pdo->prepare("UPDATE menuitems SET " . implode(", ", $updateFields) . " WHERE itemId = :itemId");
            if ($stmt->execute($params)) {
                echo "Item updated successfully!";
            } else {
                echo "Update failed.";
            }
        } else {
            echo "No changes detected.";
        }
    }

    // Handle delete action
    if ($_POST["action"] == "delete") {
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
