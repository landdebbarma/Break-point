<?php

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "breakpoint";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        function generateUUID() {
          $data = openssl_random_pseudo_bytes(16);
          $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 0100
          $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set bits 6-7 to 10
          return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
    
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uuid = generateUUID();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $stmt = $pdo->prepare("INSERT INTO users (uuid, username, email, hashedPassword) VALUES (:uuid, :username, :email, :hashedPassword)");
    
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashedPassword', $hashedPassword);
            $stmt->execute();
    
            echo "<script>
                alert('You have been registered successfully.');
                window.location.href = '../login';
            </script>";
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
                <h4>Register</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username here" required>
                    <span id="usernameError" style="color: red; display: none;">Invalid username. Only letters and numbers are allowed.</span>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email here" required>
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


    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>