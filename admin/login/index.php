<?php
  session_start();

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

          $stmt = $pdo->prepare("SELECT hashedPassword FROM users WHERE username = :username");
          $stmt->bindParam(':username', $username);
          $stmt->execute();

          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($user && password_verify($password, $user['hashedPassword'])) {
              $_SESSION['loggedin'] = true;
              echo "<script>
                      alert('You have logged in successfully.');
                      window.location.href = '../'; // Change this to your intended redirect path
                    </script>";
          } else {
              echo "Invalid username or password.";
          }
      }
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Page</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    
    <div class="py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h4>Login to Admin</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username here" onkeyup="validateUsername()">
                    <span id="usernameError" style="color: red; display: none;">Invalid username. Only letters and numbers are allowed.</span>
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Enter your password here">
                  </div>
                  
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="p-4">
                To be used as auth options in future.
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <script>
      function validateUsername() {
          const usernameInput = document.getElementById('username');
          const errorSpan = document.getElementById('usernameError');
          const username = usernameInput.value;

          if (username === '') {
              errorSpan.textContent = 'Username required';
              errorSpan.style.display = 'block'; 
          } 
          
          else if (!/^[a-z\d]+$/i.test(username)) {
              errorSpan.textContent = 'Invalid username. Only letters and numbers are allowed.';
              errorSpan.style.display = 'block'; 
          } 
          else {
              errorSpan.style.display = 'none';
          }
      }
    </script>

    

    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>