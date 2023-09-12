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
        <?php
        $orderData = getTableWithWhere("order_tbl", "user_id='$_SESSION[userId]' ORDER BY id DESC, FIELD(status, 'pending', 'preparing', 'to claim', 'claimed', 'declined', 'canceled') ASC");

        foreach ($orderData as $order) :
        ?>
          <div class="card mb-4">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <h5 class="card-title">
                    Order #: <?= $order->order_number ?>
                  </h5>
                </div>
                <div class="col-md-6">
                  <h6 class="card-title text-md-right">
                    Date Ordered: <?= date("m-d-Y", strtotime($order->date_ordered)) ?>
                  </h6>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <h5 class="card-title">
                    Status:
                    <?php
                    $badgeColor = "";

                    switch ($order->status) {
                      case "pending":
                        $badgeColor = "warning";
                        break;
                      case "preparing":
                        $badgeColor = "primary";
                        break;
                      case "to claim":
                        $badgeColor = "info";
                        break;
                      case "claimed":
                        $badgeColor = "success";
                        break;
                      case "canceled":
                      case "declined":
                        $badgeColor = "danger";
                        break;
                      default:
                        $badgeColor = "secondary";
                        null;
                    }
                    ?>
                    <span class="badge badge-<?= $badgeColor ?>">
                      <?= ucfirst($order->status) ?>
                    </span>

                  </h5>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <h5 class="card-title">
                    Note: <?= $order->note ?>
                  </h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="site-blocks-table">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <!-- <th class="product-thumbnail">Prescription</th> -->
                      <th class="product-thumbnail">Image</th>
                      <th class="product-name">Product</th>
                      <th class="product-name">Dosage</th>
                      <th class="product-price">Price</th>
                      <th class="product-quantity">Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $cartData = getTableWithWhere("cart", "user_id='$_SESSION[userId]' and order_id='$order->id'");
                    foreach ($cartData as $cart) :
                      $inventoryQStr = mysqli_query(
                        $conn,
                        "SELECT 
                          ig.id AS 'inventory_id',
                          ig.medicine_id,
                          mp.dosage,
                          mp.medicine_name,
                          (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
                          FROM inventory_general ig
                          LEFT JOIN medicine_profile mp
                          ON mp.id = ig.medicine_id
                          WHERE ig.id = '$cart->id'
                      "
                      );
                      $inventory = mysqli_fetch_object($inventoryQStr);
                    ?>
                      <tr>
                        <td class="product-thumbnail">
                          <img src="<?= getMedicineImage($inventory->medicine_id) ?>" alt="Image" class="img-fluid">
                        </td>
                        <td class="product-name">
                          <h2 class="h5 text-black"><?= $inventory->medicine_name ?></h2>
                        </td>
                        <td><?= $inventory->dosage . "mg" ?></td>
                        <td> <?= "₱ " . number_format($inventory->price, 2, '.', ',') ?></td>
                        <td><?= $cart->quantity ?></td>
                        <!-- <td></td> -->
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-6">
                  <?php if ($order->status == "pending") : ?>
                    <button type="submit" class="btn btn-danger btn-md m-1" onclick="handleCancelOrder('<?= $order->id ?>')">
                      Cancel Order
                    </button>
                  <?php endif; ?>
                </div>
                <div class="col-md-6 text-lg-right text-md-center">
                  <span class="text-black">
                    Order Total: <?= "₱ " . number_format($order->overall_total, 2, '.', ',') ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
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
  function handleCancelOrder(orderId) {
    swal.showLoading()
    $.post(
      "<?= $SERVER_NAME ?>/backend/nodes?action=cancel_order", {
        order_id: orderId
      },
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
  }

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

  function handleCheckout() {
    $("#uploadPrescription").modal("show")
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
</script>

</html>