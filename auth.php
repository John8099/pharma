<?php include("./backend/nodes.php") ?>
<!DOCTYPE html>
<html lang="en">

<?php include("./admin/components/header.php") ?>

<body>

  <div class="auth-wrapper">
    <div class="auth-content container">
      <div class="card">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="card-body">

              <?php if ($_GET["page"] == "sign-in") : ?>
                <form method="POST" id="form-sign-in">
                  <h4 class="mb-3 f-w-400">Sign in</h4>
                  <input type="text" value="user" name="role" hidden readonly>

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email address" required />
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" name="password" id="inputPass" class="form-control" placeholder="Password" required />
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
                  <button type="button" class="btn btn-danger mb-4" onclick="handleCancel()">Cancel</button>

                  <p class="mb-0 text-muted">
                    Don't have an account?
                    <a href="#" class="f-w-400" onclick="changeUrl('sign-up')">
                      Signup
                    </a>
                  </p>
                </form>

              <?php elseif ($_GET["page"] == "sign-up") : ?>

                <form id="form-sign-up" method="POST">
                  <h4 class="mb-3 f-w-400">Sign up</h4>
                  <input type="text" value="user" name="role" hidden readonly>
                  <input type="text" value="register" name="action" hidden readonly>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-user"></i></span>
                    </div>
                    <input type="text" name="fname" class="form-control" placeholder="First name" required>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-user"></i></span>
                    </div>
                    <input type="text" name="mname" class="form-control" placeholder="Middle name">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-user"></i></span>
                    </div>
                    <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-mail"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="feather icon-lock"></i></span>
                    </div>
                    <input type="password" name="password" id="inputPass" class="form-control" placeholder="Password" required>
                  </div>

                  <div class="form-group text-left mt-2">
                    <div class="checkbox checkbox-primary d-inline">
                      <input type="checkbox" id="showPass" />
                      <label for="showPass" class="cr">
                        Show Password
                      </label>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary mb-4">Sign up</button>
                  <button type="button" class="btn btn-danger mb-4" onclick="handleCancel()">Cancel</button>

                  <p class="mb-2">
                    Already have an account?
                    <a href="#" class="f-w-400" onclick="changeUrl('sign-in')">
                      Sign in
                    </a>
                  </p>
                </form>
              <?php else : ?>
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

  <?php include("./admin/components/scripts.php") ?>

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
            changeUrl()
          }

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });

    })
    $("#form-sign-up").on("submit", function(e) {
      swal.showLoading()
      e.preventDefault()

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=addUser",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Thank you!" : 'Error!',
            text: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => resp.success ? changeUrl() : undefined)

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
        $("#inputPass").prop("type", "text")
      } else {
        $("#inputPass").prop("type", "password")
      }
    })

    function changeUrl(action = "register") {
      let newUrl = "";
      const urlLoc = window.location
      const url = new URL(`${urlLoc.origin}${urlLoc.pathname}${urlLoc.search}`)
      const params = new URLSearchParams(url.search);

      if (action === "sign-in") {
        if (history.pushState) {
          newUrl = `${urlLoc.origin}${urlLoc.pathname}?page=sign-in&&url=${encodeURIComponent(params.getAll("url"))}`
        }
      } else if (action === "sign-up") {
        if (history.pushState) {
          newUrl = `${urlLoc.origin}${urlLoc.pathname}?page=sign-up&&url=${encodeURIComponent(params.getAll("url"))}`
        }
      } else {
        if (history.pushState) {
          newUrl = params.getAll("url")
        }
      }
      window.location.replace(newUrl)
    }

    function handleCancel() {
      const urlLoc = window.location
      const url = new URL(`${urlLoc.origin}${urlLoc.pathname}${urlLoc.search}`)
      const params = new URLSearchParams(url.search);

      window.location.replace(params.getAll("url"))
    }
  </script>
</body>


</html>