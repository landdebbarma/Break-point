<?php include('../includes/header.php')?>
<?php include('../includes/navbar.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Panel</title>

    <style>
        
     body {
    font-family: Arial, sans-serif;
    background-color: white;
    margin: -20px;
    padding: 20px;
}

.settings-panel {
    max-width: 600px;
    margin: auto;
    background: white;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    padding: 20px;
}

h3 {
    text-align: center;
    color: black;
}
h3:hover{
    color:red;
}

.section {
    margin-top:50px;
 margin-bottom: 20px;

}

h5 {
    font-size: 20px;
    margin-bottom: 10px;
    color: black;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 40px;
}

button {
    background-color:cornflowerblue;
    color: white;
    border: none;
    border-radius: 40px;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 15px;
    width: 100%;
}

button:hover {
    background-color: black;
    color:white;
}



    </style>
</head>
<body>
    <div class="settings-panel">
        <h3>Account Settings</h3>
        
        <!-- Change Password box -->
        <div class="section">
            <h5> password change </h5>
            <form id="change-password-form">
                <label for="current-password">Current Password</label>
                <input type="password" id="current-password" required>
                
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" required>
                
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" required>
                
                <button type="submit">Change Password</button>
            </form>
        </div>

        <!-- Forgot Password box  minu -->

        <div class="section">
            <h5>forgot password</h5>
            <form id="forgot-password-form">
                <label for="forgot-email">Email Address</label>
                <input type="email" id="forgot-email" required>
                
                <button type="submit">Send Reset Link</button>
            </form>
        </div>

        <!-- Change Username box minu -->

        <div class="section">
            <h5>Change username</h5>
            <form id="change-username-form">
                <label for="current-username">Current user name</label>
                <input type="text" id="current-username">
                
                <label for="new-username">new username</label>
                <input type="text" id="new-username" required>
                
                <button type="submit">Change username</button>
            </form>
        </div>

        <!-- Change Email box -->

        <div class="section">
            <h5>Change email</h5>
            <form id="change-email-form">
                <label for="current-email">current email</label>
                <input type="email" id="current-email">
                
                <label for="new-email">new email</label>
                <input type="email" id="new-email" required>
                
                <label for="confirm-email">confirm New Email</label>
                <input type="email" id="confirm-email" required>
                
                <button type="submit">Change email</button>
            </form>
        </div>
    </div>
</body>
</html>









<?php include('../includes/footer.php')?>