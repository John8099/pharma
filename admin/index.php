<?php include("../backend/nodes.php") ?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>

  <div class="auth-wrapper">
    <div class="auth-content container">
      <div class="card">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="card-body">
              <h4 class="mb-3 f-w-400">Sign in</h4>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="feather icon-mail"></i></span>
                </div>
                <input type="email" class="form-control" placeholder="Email address" />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="feather icon-lock"></i></span>
                </div>
                <input type="password" class="form-control" placeholder="Password" />
              </div>

              <div class="form-group text-left mt-2">
                <div class="checkbox checkbox-primary d-inline">
                  <input type="checkbox" id="showPass" />
                  <label for="checkbox-fill-a1" class="cr">
                    Show Password
                  </label>
                </div>
              </div>

              <button class="btn btn-primary mb-4">Sign In</button>

              <p class="mb-0 text-muted">
                Don't have an account?
                <a href="#" class="f-w-400" onclick="changeUrl('sign-up')">
                  Signup
                </a>
              </p>
            </div>
          </div>
          <div class="col-md-6 d-none d-md-block">
            <img src="<?= $SERVER_NAME ?>/admin/assets/images/auth-bg.jpg" alt="" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("./components/scripts.php") ?>

</body>

</html>