<?php include('../../includes/header.php')?>

    <h1>Forgot password</h1>

    <div class="py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h4>Reset Password</h4>
              </div>
              <div class="card-body">
                <form id="otpForm" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email here"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                  </div>
                  <button type="submit" id="sendOtpButton" name="register" class="btn btn-primary">Send OTP</button>
                  <div class="mb-3">
                    <label class="form-label">Enter OTP </label>
                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP recieved in email" required>
                  </div>
                  <button type="submit" name="verify" id="verifyButton" class="btn btn-primary">Verify</button>
                </form>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <script>
      document.getElementById('sendOtpButton').addEventListener('click', function(event) {
          event.preventDefault();
          
          const email = document.getElementById('email').value;
          const formData = new FormData();
          formData.append('email', email);

          fetch('sendOTP.php', {
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

          const email = document.getElementById('email').value;
          const otp = document.getElementById('otp').value;
          const formData = new FormData();
          formData.append('otp', otp);
          formData.append('email', email);

          fetch('verify.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.text())
          .then(data => {
              alert(data); 
              
              if (data.includes("OTP verified successfully")) {
                  window.location.href = 'changePass.php';
              }
          })
          .catch(error => console.error('Error:', error));
      });
    </script>


<?php include('../../includes/footer.php')?>