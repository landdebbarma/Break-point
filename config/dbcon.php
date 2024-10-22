<?php

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "breakpoint";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
    
            echo "User registered successfully!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>