<?php
    session_start();

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

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

                $stmt = $conn->prepare("UPDATE users SET hashedPassword = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashedPassword, $email);

                if ($stmt->execute()) {
                    $alertMessage = "Password changed successfully!";

                    session_destroy();

                    header("refresh:2;url=../");
                    exit();
    
                } else {
                    echo "Error changing password: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Passwords do not match.";
            }

            $conn->close();
        }
    } else {
        echo "Please verify your email before changing your password.";
    }
?>



<?php include('../../includes/header.php')?>

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
                <form id="passChange" method="POST">
                  <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
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

    <script>
        window.onload = function() {
            <?php if ($alertMessage): ?>
                alert("<?php echo $alertMessage; ?>"); 
            <?php endif; ?>
        }
    </script>

<?php include('../../includes/footer.php')?>