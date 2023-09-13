<?php
include("../../backend/nodes.php");
if (!$_SESSION["email"]) {
  header("location: ../");
}
$user = getSingleDataWithWhere("users", "email='$_SESSION[email]'");
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
                <form method="POST" id="form-sign-in">
                  <input type="text" name="userId" value="<?= $user->id ?>" hidden readonly>
                  <input type="text" name="role" value="<?= $user->role ?>" hidden readonly>

                  <div class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                      <div class="w-50">
                        <img src="<?= getAvatar($user->id) ?>" class="img-thumbnail rounded-circle ">
                      </div>
                    </div>
                  </div>
                  <input type="email" name="email" value="<?= $user->email ?>" hidden readonly />
                  <div class="input-group mb-3 mt-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" class="form-control inputPassword" name="password" placeholder="Password" required>
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
    $("#form-sign-in").on("submit", function(e) {
      swal.showLoading()
      e.preventDefault()

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=login",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          if (!resp.success) {
            swal.fire({
              title: 'Error!',
              text: resp.message,
              icon: 'error',
            })
          } else {
            if (resp.success && resp.isNew === "1") {
              swal.fire({
                title: "Your account is newly created",
                text: "Would you like to change the password?",
                icon: "question",
                confirmButtonText: "Yes",
                cancelButtonColor: "#dc3545",
                showCancelButton: true,
                cancelButtonText: "No"
              }).then((d) => {
                if (d.isConfirmed) {
                  window.location.href = ("<?= $SERVER_NAME ?>/admin/views/change-password");
                } else {
                  window.location.replace("<?= $SERVER_NAME ?>/admin/views/dashboard");
                }
              });
            } else {
              window.location.replace("<?= $SERVER_NAME ?>/admin/views/dashboard");
            }
          }

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });

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