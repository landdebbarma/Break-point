<?php
session_start();
require '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateOTP() {
    return rand(100000, 999999);
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $otp = generateOTP();

    $conn = new mysqli('localhost', 'root', '', 'breakpoint');
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        exit();
    }

    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not found. Please register first.";
    } else {
        $checkEmailStmt->close();

        $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
        if (!$stmt) {
            echo "Failed to prepare statement: " . $conn->error;
            exit();
        }
        $stmt->bind_param("ss", $otp, $email);
        $stmt->execute();

        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'coolmilibhagat@gmail.com';
            $mail->Password   = 'oivy mhej vagh cyij';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('coolmilibhagat@gmail.com', 'BreakPoint');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'OTP Verification';
            $mail->Body    = 'Your OTP is <strong>' . $otp . '</strong>';
            $mail->AltBody = 'Your OTP is ' . $otp;

            $mail->send();
            echo "OTP has been sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send OTP! Mailer Error: {$mail->ErrorInfo}";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
