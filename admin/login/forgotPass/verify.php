<?php
    session_start();
    if (isset($_POST['otp']) && isset($_POST['verify-email'])) {
        $email = $_SESSION['email'];
        $entered_otp = $_POST['otp'];

        $conn = new mysqli('localhost', 'root', '', 'breakpoint');
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }

        $stmt = $conn->prepare("SELECT otp, uuid FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($entered_otp == $row['otp']) {
            $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            $_SESSION['uuid'] = $row['uuid'];
            $_SESSION['otp_verified'] = true;

            $stmt = $conn->prepare("UPDATE users SET otp = NULL WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            echo "OTP verified successfully";
        } else {
            echo "Invalid OTP!";
        }

        $stmt->close();
        $conn->close();
    }
?>
