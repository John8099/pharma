<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php"); ?>

<body class="">

  <?php include("../components/side-nav.php"); ?>
  <?php include("../components/header-nav.php"); ?>

  <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
      <div class="pcoded-content">
        <div class="pcoded-inner-content">
          <div class="main-body">
            <div class="page-wrapper">
              <!-- [ breadcrumb ] start -->
              <?php include("../components/page-header.php") ?>
              <!-- [ breadcrumb ] end -->

              <!-- [ Main Content ] start -->

              <div class="row">
                <div class="col-md-12">
                  <?php $order = getSingleDataWithWhere("order_tbl", "id='$_GET[id]'"); ?>
                  <div class="card">
                    <div class="card-header p-2 ml-2 mt-2">
                      <h4 class="card-title">
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
                        <br>
                        Order number: <?= $order->order_number ?>

                        <button type="button" onclick="return window.history.back()" class="btn btn-secondary btn-sm float-right">
                          Go Back
                        </button>
                      </h4>
                      <?php if ($order->note) : ?>
                        <h4>Note:
                          <span><?= nl2br($order->note) ?></span>
                        </h4>
                      <?php endif; ?>

                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="customerOrderDetails" class="table table-hover table-bordered table-striped ">
                            <thead>
                              <tr>
                                <th>Prescription</th>
                                <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                                <th>Dosage</th>
                                <th>Regular</th>
                                <th>Discounted</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Date Ordered</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $orderDetails = getTableWithWhere("order_details", "order_id='$_GET[id]'");
                              $orderTotal = 0;
                              $discountedTotal = 0.00;
                              foreach ($orderDetails as $detail) :
                                $orderTotal += $detail->order_subtotal;
                                $order = getSingleDataWithWhere("order_tbl", "id='$detail->order_id'");

                                $medicineQ = mysqli_query(
                                  $conn,
                                  "SELECT 
                                    ig.id AS 'inventory_id',
                                    ig.medicine_id,
                                    mp.medicine_name,
                                    mp.generic_name,
                                    mp.dosage,
                                    ig.product_number,
                                    (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                                    (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
                                  FROM inventory_general ig
                                  LEFT JOIN medicine_profile mp
                                  ON mp.id = ig.medicine_id
                                  WHERE ig.id = '$detail->inventory_general_id' and ig.is_returned <> '1'
                              "
                                );
                                $inventory = mysqli_fetch_object($medicineQ);

                                $imgSrc = getMedicineImage($inventory->medicine_id);
                                $exploded =  explode("/", $imgSrc);
                                $alt = $exploded[count($exploded) - 1];

                                $prescriptionSrc = getPrescriptionImg($order->id);
                                $explodedPres =  explode("/", $prescriptionSrc);
                                $altPres = $explodedPres[count($explodedPres) - 1];

                                $discounted = getDiscounted($inventory->inventory_id, $inventory->price);
                                $discountedTotal += doubleval($discounted) * intval($detail->quantity);
                              ?>
                                <tr>
                                  <td class="align-middle">
                                    <?php if ($prescriptionSrc) : ?>
                                      <img onclick="handleOpenModalImg('divModalPrescription<?= $inventory->inventory_id ?>')" src="<?= $prescriptionSrc ?>" class="rounded modalImg" width="60px">
                                    <?php else : ?>
                                      <em class="text-muted">N/A</em>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <button type="button" class="btn btn-link btn-lg p-0 m-0 " onclick="handleOpenModalImg('divModalImage<?= $inventory->inventory_id ?>')">
                                      <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?>
                                    </button>
                                  </td>
                                  <td><?= $inventory->dosage ?></td>
                                  <td><?= number_format($inventory->price, 2, '.', ',') ?></td>
                                  <td><?= number_format($discounted, 2, '.', ',') ?></td>
                                  <td><?= $detail->quantity ?></td>
                                  <td><?= number_format($detail->order_subtotal, 2, '.', ',') ?></td>
                                  <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                                </tr>
                                <div id='divModalImage<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                                  <span class='close' onclick='handleClose(`divModalImage<?= $inventory->inventory_id ?>`)'>&times;</span>
                                  <img class='div-modal-content' src="<?= $imgSrc  ?>">
                                  <div id="imgCaption"><?= $alt ?></div>
                                </div>

                                <?php if ($prescriptionSrc) : ?>
                                  <div id='divModalPrescription<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                                    <span class='close' onclick='handleClose(`divModalPrescription<?= $inventory->inventory_id ?>`)'>&times;</span>
                                    <img class='div-modal-content' src="<?= $prescriptionSrc  ?>">
                                    <div id="imgCaption"><?= $altPres ?></div>
                                  </div>
                                <?php endif ?>

                              <?php endforeach; ?>

                            </tbody>
                          </table>

                        </div>
                      </div>

                      <div class="row justify-content-end">
                        <div class="col-md-4">

                          <div class="row">
                            <div class="col-6">
                              <h4>
                                Regular Total
                              </h4>
                            </div>
                            <div class="col-6">
                              <h4 id="subTotal">
                                <?= number_format($orderTotal, 2, '.', ',') ?>
                              </h4>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-6">
                              <h4>
                                Discounted Total
                              </h4>
                            </div>
                            <div class="col-6">
                              <h4 id="discount">
                                <?= number_format($discountedTotal, 2, '.', ',') ?>
                              </h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end p-2">
                      <?php
                      $arrayIn = array("pending", "preparing", "to claim");
                      if (in_array($order->status, $arrayIn)) :
                        if ($order->status == "to claim") :
                      ?>
                          <button type="button" onclick="handleClaimOrder()" class="btn btn-primary btn-sm m-2">
                            Process Claim
                          </button>
                        <?php else : ?>
                          <?php if ($order->status == "pending") : ?>
                            <button type="button" onclick="openModalReason('<?= $order->id ?>')" class="btn btn-danger btn-sm">
                              Decline
                            </button>
                            <button type="button" onclick="handleChangeOrderStatus('<?= $order->id ?>', 'preparing')" class="btn btn-primary btn-sm">
                              Set Preparing
                            </button>
                          <?php elseif ($order->status == "preparing") : ?>
                            <button type="button" onclick="handleChangeOrderStatus('<?= $order->id ?>', 'to claim')" class="btn btn-info btn-sm">
                              Set to Claim
                            </button>
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>

                  </div>
                </div>
              </div>
              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCheckOut" tabindex="-1" role="dialog" aria-labelledby="Checkout" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-secondary">
              Checkout
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form id="checkoutForm" method="POST">
            <input type="text" value="<?= $_GET["id"] ?>" name="order_id" readonly hidden>
            <div class="modal-body">

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Discount Type</label>
                <div class="col-sm-9">
                  <select name="discountType" id="inputDiscountType" class="form-control" required>
                    <option value="regular">Regular</option>
                    <option value="pwd">PWD</option>
                    <option value="senior citizen">Senior Citizen</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Total</label>
                <div class="col-sm-9">
                  <input type="number" name="total" id="inputTotal" value="<?= number_format($orderTotal, 2, '.', ',') ?>" step=".01" class="form-control" readonly required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Amount </label>
                <div class="col-sm-9">
                  <input type="number" name="amount" id="inputAmount" step=".01" class="form-control" required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Change</label>
                <div class="col-sm-9">
                  <input type="number" name="change" id="inputChange" value="0.00" step=".01" class="form-control" readonly required>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" id="btnClear" class="btn btn-danger btn-sm m-2 d-none">
                Clear Discount
              </button>

              <!-- <button type="button" id="btnDiscount" class="btn btn-warning btn-sm m-2">
                Add Discount
              </button> -->

              <button type="submit" id="btnProceed" class="btn btn-primary btn-sm m2 disabled" disabled>Proceed</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="modalReason" tabindex="-1" role="dialog" aria-labelledby="New Brand" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-secondary">
              Reason form
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="uploadReason" method="POST">
            <input type="text" value="<?= $order->id ?>" name="order_id" hidden>
            <div class="modal-body">
              <div class="form-group">
                <label>Reason</label>
                <textarea name="note" class="form-control" cols="30" rows="10"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>


  <?php include("../components/scripts.php") ?>
  <script>
    $("#uploadReason").on("submit", function(e) {
      e.preventDefault();
      swal.showLoading();

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=decline_order",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => resp.success ? window.location.reload() : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    })

    function openModalReason(orderId) {
      $("#modalReason").modal("show")
    }

    $("#checkoutForm").on("submit", function(e) {
      e.preventDefault()
      swal.showLoading();

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=claim_online_order",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => resp.success ? window.location.href = `print-receipt?id=${resp.invoice_id}` : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    })

    $("#modalCheckOut").on("hidden.bs.modal", function(e) {
      $("#inputAmount").val("");
      $("#inputChange").val("0.00")
      $("#btnProceed").prop("disabled", true)
    })

    $("#inputDiscountType").on("change", function(e) {
      const discountType = e.target.value;

      if (discountType !== "regular") {
        const discount = $("#discount").text().replace("₱ ", "").trim();
        $("#inputTotal").val(discount)
      } else {
        const subTotal = $("#subTotal").text().replace("₱ ", "").trim();
        $("#inputTotal").val(subTotal)
      }

      handleTriggerChange()
    })

    $("#inputChange").on("change", function() {
      const amount = Number($("#inputAmount").val())
      const total = Number($("#inputTotal").val())

      const change = (amount - total)
      if (change <= 0) {
        $(this).val("0.00")
      } else {
        $(this).val(change.toFixed(2))
        $("#btnProceed").prop("disabled", false);
        $("#btnProceed").removeClass("disabled");
      }
    })

    function handleTriggerChange() {
      $("#inputChange").change()
    }

    $("#inputAmount").on("input", function() {
      handleTriggerChange()
    })

    function handleClaimOrder() {
      $("#modalCheckOut").modal("show")
    }

    function handleChangeOrderStatus(orderId, status) {
      swal.showLoading()
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=change_order_status", {
          order_id: orderId,
          status: status
        },
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => resp.success ? window.location.reload() : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }

    $(document).ready(function() {
      const tableId = "#customerOrderDetails";
      var table = $(tableId).DataTable({
        paging: false,
        lengthChange: false,
        ordering: false,
        info: false,
        autoWidth: false,
        responsive: true,
      });

    });
  </script>
</body>

</html>