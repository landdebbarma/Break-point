<?php
session_start();

include_once("../../config/dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST["category_name"];
    $categoryId = bin2hex(random_bytes(16));
    $stmt = $pdo->prepare("INSERT INTO menucategory (categoryId, categoryName) VALUES (:categoryId, :Name)");
    $stmt->bindParam(':categoryId', $categoryId);
    $stmt->bindParam(':Name', $category_name);
    $stmt->execute();

    echo "Category uploaded successfully!";
}
?>