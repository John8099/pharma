<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php") ?>

<body>

  <div class="auth-wrapper">
    <div class="auth-content container">
      <div class="card">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="card-body">
              <?php if ($user) : ?>
                <form method="POST" id="form-change-password">
                  <input type="text" name="userId" value="<?= $user->id ?>" hidden readonly>
                  <h4 class="mb-3 f-w-400">Change Password</h4>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" class="form-control inputPassword" name="old_password" id="inputOldPassword" placeholder="Old Password" required>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" class="form-control inputPassword" name="new_password" id="inputNewPassword" placeholder="New Password" require>
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" class="form-control inputPassword" name="confirm_password" id="inputConfirmPassword" placeholder="Confirm Password" required>
                  </div>

                  <div class="form-group text-left mt-2">
                    <div class="checkbox checkbox-primary d-inline">
                      <input type="checkbox" id="showPass" />
                      <label for="showPass" class="cr">
                        Show Password
                      </label>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary mb-4">Sign In</button>

                </form>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-6 d-none d-md-block">
            <img src="<?= $SERVER_NAME ?>/admin/assets/images/auth-bg.jpg" alt="" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>

  <script>
    $("#form-change-password").on("submit", function(e) {
      swal.showLoading()
      e.preventDefault()

      const oldPass = $("#inputOldPassword").val()
      const newPass = $("#inputNewPassword").val()
      const confirmPass = $("#inputConfirmPassword").val()

      if (newPass !== confirmPass) {
        swal.fire({
          title: "Error",
          html: "New Password and Confirm Password not match",
          icon: "error",
        })
      } else {
        $.post(
          "<?= $SERVER_NAME ?>/backend/nodes?action=change_password",
          $(this).serialize(),
          (data, success) => {
            const resp = $.parseJSON(data)
            swal.fire({
              title: resp.success ? "Success!" : "Error!",
              html: resp.message,
              icon: resp.success ? "success" : "error"
            }).then(() => resp.success ? window.location.replace("<?= $SERVER_NAME ?>/admin/views/dashboard") : undefined)
          })
      }
      
    })

    $("#showPass").on("click", function() {
      if ($(this).prop("checked") == true) {
        $(".inputPassword").prop("type", "text")
      } else {
        $(".inputPassword").prop("type", "password")
      }
    })
  </script>
</body>

</html>