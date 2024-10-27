<?php include('../includes/header.php') ?>
<?php include('../includes/navbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings Page</title>
    <link href="/break-point/admin/assets/css/bootstrap.min.css" rel="stylesheet">

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
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        h3 {
            text-align: center;
            color: black;
        }

        h3:hover {
            color: red;
        }

        .section {
            margin-top: 50px;
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
            background-color: cornflowerblue;
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
            color: white;
        }
    </style>
</head>

<body>
    <div class="settings-panel">
        <h3>Account Settings</h3>

        <!-- Change Password box -->
        <div class="section">
            <h5>Change Password</h5>
            <form id="change-password-form">
                <input type="hidden" name="action" value="change-password">
                <div class="form-floating mb-3">
                    <input type="password" id="current-password" class="form-control" name="current-password" placeholder="" required>
                    <label for="floatingInput">Current Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" id="new-password" class="form-control" name="new-password" placeholder="" required>
                    <label for="floatingInput">New Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" id="confirm-password" class="form-control" name="confirm-password" placeholder="" required>
                    <label for="floatingInput">Confirm New Password</label>
                </div>
                <button type="submit">Change Password</button>

            </form>
            <a href="../login/forgotPass/">Forgot Password?</a>
        </div>

        <div class="section">
            <h5>Change Username</h5>
            <form id="change-username-form">
                <input type="hidden" name="action" value="change-username">
                <div class="form-floating mb-3">
                    <input type="text" id="current-username" name="current-username" class="form-control" placeholder="" required>
                    <label for="floatingInput">Current Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="new-username" name="new-username" class="form-control" placeholder="" required>
                    <label for="floatingInput">New Username</label>
                </div>
                <button type="submit">Change Username</button>
            </form>
        </div>

        <div class="section">
            <h5>Verify Email</h5>
            <form id="verify-email-form" method="POST">
                <div class="form-floating mb-3" style="display: flex; align-items: center;">
                    <input type="text" id="verify-email" name="verify-email" class="form-control" placeholder="" style="flex: 0 0 70%; margin-right: 10px;" required>
                    <label for="floatingInput">Enter Email</label>
                    <button type="submit" id="sendOtpButton" name="register" onclick="submitForm('/break-point/admin/login/forgotPass/sendOTP.php')">Send OTP</button>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="otp" name="otp" class="form-control" placeholder="">
                    <label for="floatingInput">Enter OTP</label>
                </div>
                <button type="submit" name="verify" id="verifyButton" onclick="submitForm('/break-point/admin/login/forgotPass/verify.php')">Verify</button>
            </form>
        </div>

        <div class="section">
            <h5>Change Email</h5>
            <form id="change-email-form">
                <input type="hidden" name="action" value="change-email">
                <div class="form-floating mb-3">
                    <input type="email" id="current-email" name="current-email" class="form-control" placeholder="">
                    <label for="floatingInput">Current Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" id="new-email" name="new-email" class="form-control" placeholder="" required>
                    <label for="floatingInput">New Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" id="confirm-email" name="confirm-email" class="form-control" placeholder="" required>
                    <label for="floatingInput">Confirm New Email</label>
                </div>
                <button type="submit">Change Email</button>
            </form>
        </div>
    </div>


    <?php include('../includes/footer.php') ?>

    <script>
        async function handleFormSubmission(formId) {
            const form = document.getElementById(formId);
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(form);

                try {
                    const response = await fetch("settings.php", {
                        method: "POST",
                        body: formData
                    });
                    const result = await response.text();

                    alert(result);

                    if (result.toLowerCase().includes("success")) {
                        form.reset();
                    }
                } catch (error) {
                    console.error("Error:", error);
                    document.getElementById("response-message").innerText = "An error occurred. Please try again.";
                }
            });
        }

        handleFormSubmission("change-password-form");
        handleFormSubmission("change-username-form");
        handleFormSubmission("change-email-form");
    </script>
    <script>
        document.getElementById('sendOtpButton').addEventListener('click', function(event) {
            event.preventDefault();

            const email = document.getElementById('verify-email').value;
            const formData = new FormData();
            formData.append('verify-email', email);

            fetch('/break-point/admin/login/forgotPass/sendOTP.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                })
                .catch(error => console.error('Error:', error));
        });

        document.getElementById('verifyButton').addEventListener('click', function(event) {
            event.preventDefault();

            const email = document.getElementById('verify-email').value;
            const otp = document.getElementById('otp').value;
            const formData = new FormData();
            formData.append('otp', otp);
            formData.append('verify-email', email);

            fetch('/break-point/admin/login/forgotPass/verify.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);

                    if (data.includes("OTP verified successfully")) {
                        // Clear the form fields
                        document.getElementById('verify-email').value = '';
                        document.getElementById('otp').value = '';

                        // Optionally, redirect to another page
                        // window.location.href = 'changePass.php';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>