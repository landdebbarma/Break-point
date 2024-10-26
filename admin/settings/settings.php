<?php 
session_start();
include_once "../../config/dbcon.php"; // Adjust as necessary

// Main handler for POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'change-password':
            changePassword($pdo, $_SESSION['uuid'], $_POST);
            break;
        case 'change-username':
            changeUsername($pdo, $_SESSION['uuid'], $_POST);
            break;
        case 'change-email':
            changeEmail($pdo, $_SESSION['uuid'], $_POST);
            break;
        default:
            echo "Invalid action.";
            break;
    }
}

// Function to handle password change
function changePassword($pdo, $uuid, $data) {
    $currentPassword = $data['current-password'];
    $newPassword = $data['new-password'];
    $confirmPassword = $data['confirm-password'];

    // Fetch the current hashed password from the database
    $stmt = $pdo->prepare("SELECT hashedPassword FROM users WHERE uuid = :id");
    $stmt->execute(['id' => $uuid]);
    $user = $stmt->fetch();

    // Check if user exists and verify current password
    if ($user && password_verify($currentPassword, $user['hashedPassword'])) {
        // Check if new passwords match
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $pdo->prepare("UPDATE users SET hashedPassword = :password WHERE uuid = :id");
            echo $updateStmt->execute(['password' => $hashedPassword, 'id' => $uuid]) 
                ? "Password changed successfully." 
                : "Error updating password.";
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}

// Function to handle username change
function changeUsername($pdo, $uuid, $data) {
    $currentUsername = $data['current-username'];
    $newUsername = $data['new-username'];

    // Fetch the current username from the database
    $stmt = $pdo->prepare("SELECT username FROM users WHERE uuid = :id");
    $stmt->execute(['id' => $uuid]);
    $user = $stmt->fetch();

    // Verify the current username
    if ($user && $user['username'] === $currentUsername) {
        // Update the username in the database
        $updateStmt = $pdo->prepare("UPDATE users SET username = :username WHERE uuid = :id");
        echo $updateStmt->execute(['username' => $newUsername, 'id' => $uuid]) 
            ? "Username changed successfully." 
            : "Error updating username.";
    } else {
        echo "Current username is incorrect.";
    }
}

// Function to handle email change
function changeEmail($pdo, $uuid, $data) {
    $currentEmail = $data['current-email'];
    $newEmail = $data['new-email'];
    $confirmEmail = $data['confirm-email'];

    // Fetch the current email from the database
    $stmt = $pdo->prepare("SELECT email FROM users WHERE uuid = :id");
    $stmt->execute(['id' => $uuid]);
    $user = $stmt->fetch();

    // Verify the current email
    if ($user && $user['email'] === $currentEmail) {
        if ($newEmail === $confirmEmail) {
            // Update the email in the database
            $updateStmt = $pdo->prepare("UPDATE users SET email = :email WHERE uuid = :id");
            echo $updateStmt->execute(['email' => $newEmail, 'id' => $uuid]) 
                ? "Email changed successfully." 
                : "Error updating email.";
        } else {
            echo "New email and confirm email do not match.";
        }
    } else {
        echo "Current email is incorrect.";
    }
}
?>
