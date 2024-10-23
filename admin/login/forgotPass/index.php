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
                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email here">
                  </div>
                  <button type="submit" class="btn btn-primary">Send OTP</button>

                  <div class="mb-3">
                    <label class="form-label">Enter OTP </label>
                    <input type="otp" class="form-control" name="otp" id="otp" placeholder="Enter OTP recieved in email">
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

<?php include('../../includes/footer.php')?>