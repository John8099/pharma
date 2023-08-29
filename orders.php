<?php include("./backend/nodes.php"); ?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>

  <div class="site-wrap">

    <?php include("./components/header-nav.php") ?>

    <div class="site-section">
      <div class="container">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h5 class="card-title">
                  Order #:
                </h5>
              </div>
              <div class="col-md-6">
                <h5 class="card-title text-md-right">
                  Date Checkout:
                </h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5 class="card-title">
                  Status:
                </h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="site-blocks-table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-price">Price</th>
                    <th class="product-quantity">Quantity</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-6">
                <button type="submit" class="btn btn-danger btn-md m-1" onclick="handleCancelOrder()">
                  Cancel Order
                </button>
              </div>
              <div class="col-md-6 text-lg-right text-md-center">
                <span class="text-black">
                  Order Total: <?= "₱ " . number_format(100, 2, '.', ',') ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="modal fade" id="uploadPrescription" tabindex="-1" role="dialog" aria-labelledby="New Brand" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            Upload Prescription
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="uploadPrescriptionForm" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 mb-1 text-center">
                <label class="form-label">Please upload if you have prescription</label>
                <div class="form-group">
                  <img src="<?= getPrescriptionImg() ?>" class="rounded mx-auto d-block" style="width: 150px; height: 150px;" id="display">
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="browse">
                  <button type="button" class="btn btn-primary btn-sm" onclick="return changeImage('#formInput')">
                    Browse
                  </button>
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="clear">
                  <button type="button" class="btn btn-danger btn-sm" onclick="return clearImg('#display', '#clear', '#browse', null, 'prescription')">
                    Clear
                  </button>
                </div>
                <div class="mt-3" style="display: none;">
                  <input class="form-control form-control-sm" type="file" accept="image/*" onchange="return previewFile(this, '#display', '#clear', '#browse')" id="formInput" name="prescription_img">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <?php include("./components/scripts.php") ?>

</body>
<script>
  $("#clear").hide()

  $("#uploadPrescription").on("hidden.bs.modal", function(e) {
    const host = window.location.host === "localhost" ? "/pharma" : "";
    $("#uploadPrescriptionForm")[0].reset()

    $("#display").attr("src", `${host}/public/prescription.png`);

    $("#browse").addClass("d-flex").removeClass("d-none");
    $("#clear").addClass("d-none").removeClass("d-flex");
  })

  $("#uploadPrescriptionForm").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()

    $.ajax({
      url: '<?= $SERVER_NAME ?>/backend/nodes?action=checkout',
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = JSON.parse(data);
        swal.fire({
          title: resp.success ? 'Success!' : "Error!",
          html: resp.message,
          icon: resp.success ? 'success' : 'error',
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

  $("#form-update-cart").on("submit", function(e) {
    swal.showLoading();
    e.preventDefault();

    $.post(
      "<?= $SERVER_NAME ?>/backend/nodes?action=update_cart",
      $(this).serialize(),
      (data, status) => {
        const resp = JSON.parse(data)
        swal.fire({
          title: resp.success ? "Success!" : 'Error!',
          text: resp.message,
          icon: resp.success ? "success" : 'error',
        }).then(() => resp.success ? window.location.reload() : undefined)

      }).fail(function(e) {
      swal.fire({
        title: 'Error!',
        text: e.statusText,
        icon: 'error',
      })
    });
  })

  function handleCheckout() {
    $("#uploadPrescription").modal("show")
  }

  function handleRemoveFromCart(cartId) {
    swal.fire({
      title: "Login Required!",
      text: "You need to login first before adding to cart.",
      icon: "warning",
      showCancelButton: true,
    }).then((d) => {
      if (d.isConfirmed) {
        window.location.href = "./auth?page=sign-in&&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
      }
    });
  }

  $(".js-btn-minus").on("click", function(e) {
    e.preventDefault();
    const inputNum = $(this).closest(".input-group").find(".form-control");
    const inputVal = inputNum.val();
    const minVal = inputNum[0].min;

    if (inputVal != minVal) {
      inputNum.val(
        parseInt(
          $(this).closest(".input-group").find(".form-control").val()
        ) - 1
      );
    } else {
      inputNum.val(parseInt(minVal));
    }
  });

  $(".js-btn-plus").on("click", function(e) {
    e.preventDefault();
    const inputNum = $(this).closest(".input-group").find(".form-control");
    const inputVal = inputNum.val();
    const maxVal = inputNum[0].max;

    if (inputVal != maxVal) {
      inputNum.val(parseInt(inputVal) + 1);
    } else {
      inputNum.val(parseInt(maxVal));
    }
  });

  $(".inputQuantity").on("input", function(e) {
    const inputVal = $(this).val()
    const maxVal = $(this)[0].max;
    const minVal = $(this)[0].min;

    if (Number(maxVal) < Number(inputVal)) {
      $(this).val(parseInt(maxVal))
    }

    if (Number(minVal) > Number(inputVal)) {
      $(this).val(parseInt(minVal))
    }
  })
</script>

</html>