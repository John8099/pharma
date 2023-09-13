<?php
include("./backend/nodes.php");
if (!$isLogin) {
  header("location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>

  <div class="site-wrap">

    <?php include("./components/header-nav.php") ?>

    <div class="site-section">
      <div class="container">
        <?php if ($user) : ?>
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card">

                <div class="card-body">
                  <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="tab" href="#profile" role="tab" aria-controls="Profile" aria-selected="true">Profile</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</a>
                    </li>

                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <form id="form-profile" method="POST" enctype="multipart/form-data">

                        <?php
                        $imgSrc = getAvatar($user->id);
                        $explodedImgSrc = explode("/", $imgSrc);
                        $isDefaultImg = $explodedImgSrc[count($explodedImgSrc) - 2] == "public" ? true : false;

                        $hideBrowseButtonLogic = $isDefaultImg ? "flex" : "none";
                        $hideClearButtonLogic = $isDefaultImg ? "none" : "flex";
                        ?>

                        <input type="text" value="<?= $user->id ?>" readonly hidden>
                        <input type="text" id="setNull" name="set_null" value="Yes" hidden>

                        <div class="form-group">
                          <img src="<?= $imgSrc ?>" class="rounded mx-auto d-block" style="width: 150px; height: 150px;" id="display">
                        </div>
                        <div class="mt-3 d-<?= $hideBrowseButtonLogic ?> justify-content-center" id="browse">
                          <button type="button" class="btn btn-primary btn-sm" onclick="return changeImage('#inputFile')">
                            Browse
                          </button>
                        </div>
                        <div class="mt-3 d-<?= $hideClearButtonLogic ?> justify-content-center" id="clear">
                          <button type="button" class="btn btn-danger btn-sm" onclick="return clearImg('#display', '#clear', '#browse')">
                            Clear
                          </button>
                        </div>
                        <div class="mt-3" style="display: none;">
                          <input class="form-control form-control-sm" type="file" accept="image/*" onchange="return previewFile(this, '#display', '#clear', '#browse')" id="inputFile" name="image">
                        </div>

                        <div class="form-group">
                          <label class="form-label">First name</label>
                          <input type="text" name="fname" class="form-control" value="<?= $user->fname ?>" placeholder="First name" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Middle name</label>
                          <input type="text" name="mname" class="form-control" value="<?= $user->mname ?>" placeholder="Middle name">
                        </div>
                        <div class="form-group">
                          <label class="form-label">Last name</label>
                          <input type="text" name="lname" class="form-control" value="<?= $user->lname ?>" placeholder="Last name" required>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" value="<?= $user->email ?>" placeholder="Email" required>
                        </div>

                        <div class="form-group d-flex justify-content-end align-items-center">
                          <button type="submit" class="btn btn-primary m-2">
                            Update
                          </button>
                          <button type="button" class="btn btn-danger m-2" onclick="return window.history.back()">
                            Cancel
                          </button>
                        </div>
                      </form>

                    </div>

                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                      <form id="form-change-password" class="row mt-3" method="POST">
                        <input type="text" value="<?= $user->id ?>" name="userId" hidden readonly>
                        <div class="col-md-12">
                          <div class="form-group form-group-default">
                            <label>Old Password</label>
                            <input type="password" class="form-control inputPassword" name="old_password" id="inputOldPassword" required>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group form-group-default">
                            <label>New Password</label>
                            <input type="password" class="form-control inputPassword" name="new_password" id="inputNewPassword" require>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group form-group-default">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control inputPassword" name="confirm_password" id="inputConfirmPassword" required>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group text-left mt-2">
                            <div class="checkbox checkbox-primary d-inline">
                              <input type="checkbox" id="showPass" />
                              <label for="showPass" class="cr">
                                Show Password
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 text-right mt-3 mb-3">
                          <button type="submit" class="btn btn-warning">Change</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <?php include("./components/scripts.php") ?>

</body>
<script>
  $("#form-change-password").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()

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
          }).then(() => resp.success ? window.location.reload() : undefined)
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
  
  $("#form-profile").on("submit", function(e) {
    e.preventDefault();
    swal.showLoading();
    $.ajax({
      url: '<?= $SERVER_NAME ?>/backend/nodes?action=update_profile',
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = JSON.parse(data);
        swal.fire({
          title: resp.success ? "Success!" : 'Error!',
          html: resp.message,
          icon: resp.success ? "success" : 'error',
        }).then(() => resp.success ? window.location.reload() : undefined)
      },
      error: function(data) {
        swal.fire({
          title: 'Oops...',
          text: 'Something went wrong.',
          icon: 'error',
        })
      }
    });

  })

  function changeImage(inputId) {
    $(inputId).click();
    $(`#setNull`).val("No");
  };

  function clearImg(imgDisplayId, divClearId, divBrowseId) {
    $("input[type=file]").val("");
    $(imgDisplayId).attr("src", `<?= $SERVER_NAME ?>/public/default.png`);

    $(divClearId).addClass("d-none").removeClass("d-flex");
    $(divBrowseId).addClass("d-flex").removeClass("d-none");

    $(`#setNull`).val("Yes");
  };

  function previewFile(input, imgDisplayId, divClearId, divBrowseId) {
    let file = $(input).get(0).files[0];

    if (file) {
      let reader = new FileReader();

      reader.onload = function() {
        $(imgDisplayId).attr("src", reader.result);
      };

      reader.readAsDataURL(file);

      $(divClearId).addClass("d-flex").removeClass("d-none");
      $(divBrowseId).addClass("d-none").removeClass("d-flex");
    }
  };
</script>

</html>