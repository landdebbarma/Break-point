<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    
    header("Location: login");
    exit;
}
?>

<?php include('includes/header.php')?>
<?php include('includes/navbar.php')?>
<h1>Hello, Admin anna!</h1>
    <button class="btn btn-primary">test</button>
<?php include('includes/footer.php')?>
