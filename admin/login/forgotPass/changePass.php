<?php
session_start();

$alertMessage = ''; // Initialize alert message

if (isset($_SESSION['uuid'])) {
    $uuid = $_SESSION['uuid'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPass = $_POST['newpass'];
        $renewPass = $_POST['renewpass'];

        $conn = new mysqli('localhost', 'root', '', 'breakpoint');
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }

        if ($newPass === $renewPass) {
            $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET hashedPassword = ? WHERE uuid = ?");
            $stmt->bind_param("ss", $hashedPassword, $uuid);

            if ($stmt->execute()) {
                $alertMessage = "Password changed successfully!";
                session_destroy();
            } else {
                $alertMessage = "Error changing password: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $alertMessage = "Passwords do not match.";
        }

        $conn->close();
    }
} else {
    $alertMessage = "Please verify your email before changing your password.";
}
?>

<?php include('../../includes/header.php'); ?>

<h1>Change password</h1>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Enter New Password</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($alertMessage): ?>
                            <div class="alert alert-info">
                                <?php echo htmlspecialchars($alertMessage); ?>
                            </div>
                            <script>
                                // Show alert and redirect after 3 seconds
                                setTimeout(function() {
                                    window.location.href = '../'; // Change to your redirect URL
                                }, 3000);
                            </script>
                        <?php endif; ?>
                        <form id="passChange" method="POST">
                            <div class="mb-3">
                                <label for="newpass" class="form-label">New Password</label>
                                <input type="password" class="form-control" name="newpass" id="newpass" placeholder="Enter new password here" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Re-Enter Password </label>
                                <input type="password" class="form-control" name="renewpass" id="renewpass" placeholder="Re-Enter new password here" required>
                            </div>
                            <button type="submit" name="submit" id="verifyButton" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('passChange').addEventListener('submit', function(event) {
        const newPass = document.getElementById('newpass').value;
        const reNewPass = document.getElementById('renewpass').value;

        if (newPass !== reNewPass) {
            event.preventDefault(); 
            alert('Passwords do not match. Please try again.'); 
        }
    });
</script>

<?php include('../../includes/footer.php'); ?>
