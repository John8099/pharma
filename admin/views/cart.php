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
              <div class="page-header mb-1">
                <div class="page-block">
                  <div class="row align-items-center">
                    <div class="col-md-12">
                      <div class="page-header-title">
                        <h5>
                          <span class="pcoded-micon mr-2">
                            <i class="fa fa-cart-plus"></i>
                          </span>
                          <span class="pcoded-mtext">
                            My Cart
                          </span>
                        </h5>
                      </div>
                      <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                          <a href="<?= $SERVER_NAME ?>/admin/views/orders">
                            Order
                          </a>
                        </li>
                        <li class="breadcrumb-item">
                          <a href="<?= $SERVER_NAME ?>/admin/views/cart">
                            My Cart
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- [ breadcrumb ] end -->

              <!-- [ Main Content ] start -->

              <div class="row mt-3 justify-content-end">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="return window.location.replace('<?= $SERVER_NAME ?>/admin/views/orders')">
                          Go back
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table id="cartTable" class="table ">
                        <thead>
                          <tr>
                            <th>Image</th>
                            <th>Generic name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($user) :
                            $cartData = mysqli_query(
                              $conn,
                              "SELECT * FROM carts WHERE user_id='$user->id'"
                            );
                            while ($cart = mysqli_fetch_object($cartData)) :
                              $getMedicine = getTableData("medicines", "medicine_id", $cart->medicine_id);
                              $medicine = $getMedicine[0];
                              $typeData = getTableData("medicine_types", "type_id", $medicine->type_id);
                              $type = count($typeData) > 0 ? $typeData[0]->name : "";

                          ?>
                              <tr class="text-center">
                                <td style="width: 130px;">
                                  <img src="<?= getMedicineImage($medicine->medicine_id) ?>" alt="Image" class="img-fluid">
                                </td>
                                <td class="h6 align-middle">
                                  <?= $medicine->generic_name ?>
                                </td>
                                <td class="h6 align-middle">
                                  <?= $type ?>
                                </td>
                                <td class="h6 align-middle">
                                  <?= "₱" . number_format($medicine->price, 2, ".") ?>
                                </td>
                                <td style="vertical-align: middle;">
                                  <div class="input-group mb-3" style="max-width: 200px;">

                                    <div class="input-group-prepend">
                                      <button class="btn btn-outline-primary" type="button" onclick="handleChangeQty('minus', '<?= $cart->cart_id ?>', <?= $medicine->price ?>, '<?= intval($medicine->quantity) + intval($cart->quantity) ?>')">
                                        <i class="fa fa-minus m-0 p-0"></i>
                                      </button>
                                    </div>

                                    <input type="text" class="form-control text-center bg-light text-dark font-weight-bold" value="<?= $cart->quantity ?>" id="inputQuantity<?= $cart->cart_id ?>" oninput="return console.log($(this))" readonly>

                                    <div class="input-group-append">
                                      <button class="btn btn-outline-primary" type="button" onclick="handleChangeQty('add', '<?= $cart->cart_id ?>', <?= $medicine->price ?>, '<?= intval($medicine->quantity) + intval($cart->quantity) ?>')">
                                        <i class="fa fa-plus m-0 p-0"></i>
                                      </button>
                                    </div>
                                  </div>
                                </td>

                                <td class="h6 align-middle">
                                  <label class="medTotal<?= $cart->cart_id ?> toTotal">
                                    <?= "₱" . number_format(floatval($medicine->price *  $cart->quantity), 2, ".") ?>
                                  </label>
                                </td>

                                <td style="vertical-align: middle;">
                                  <a href="#" onclick="handleRemoveToCart('<?= $cart->cart_id ?>', '<?= $medicine->quantity ?>')" class="h3 text-danger m-2">
                                    <i class="fa fa-times-circle" title="Remove" data-toggle="tooltip"></i>
                                  </a>
                                </td>
                              </tr>
                          <?php
                            endwhile;
                          endif;
                          ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12 text-center border-bottom mb-5">
                          <h3 class="text-black h4 text-uppercase">Order Total</h3>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <?php
                        if ($user) :
                          $cartData = mysqli_query(
                            $conn,
                            "SELECT * FROM carts WHERE user_id='$user->id'"
                          );
                          $total = 0;
                          while ($cart = mysqli_fetch_object($cartData)) :
                            $getMedicine = getTableData("medicines", "medicine_id", $cart->medicine_id);
                            $medicine = $getMedicine[0];
                        ?>
                            <div class="col-md-6">
                              <span class="text-black">
                                <?= $medicine->generic_name ?>
                                <strong class="mx-2">x</strong>
                                <?= $cart->quantity ?>
                              </span>
                            </div>
                            <div class="col-md-6 text-right">
                              <strong class="text-black medTotal<?= $cart->cart_id ?>">
                                <?= "₱" . number_format(floatval($medicine->price *  $cart->quantity), 2, ".") ?>
                              </strong>
                            </div>
                        <?php
                            $total += floatval($medicine->price *  $cart->quantity);
                          endwhile;
                        endif;
                        ?>
                      </div>
                      <hr>
                      <div class="row mb-5">
                        <div class="col-md-6">
                          <span class="text-black">Total</span>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black" id="overallTotal"><?= "₱" . number_format(floatval($total), 2, ".") ?></strong>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12 d-lg-flex justify-content-end">
                          <button class="btn btn-primary btn-block" id="btnCheckOut" onclick="window.location='checkout.html'">Checkout</button>
                        </div>
                      </div>
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

    <?php include("../components/scripts.php") ?>
    <script>
      function handleRemoveToCart(cartId, medQty) {
        swal.showLoading()
        swal
          .fire({
            title: "Are you sure you want to remove this item?",
            text: "You can't undo this action after successful deletion.",
            icon: "warning",
            confirmButtonText: "Delete",
            confirmButtonColor: "#dc3545",
            showCancelButton: true,
          })
          .then((d) => {
            if (d.isConfirmed) {
              swal.showLoading();
              $.post(
                "<?= $SERVER_NAME ?>/backend/nodes?action=remove_to_cart", {
                  cart_id: cartId,
                  medicine_qty: medQty
                },
                (data, status) => {
                  const resp = JSON.parse(data)
                  swal.fire({
                    title: resp.success ? "Success!" : 'Error!',
                    html: resp.message,
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
          });

      }

      function handleChangeQty(action, cartId, price, max) {
        const inputVal = $(`#inputQuantity${cartId}`)
        const min = 1;

        if (action === "add") {
          if (Number(inputVal.val()) < max) {
            changeQtyInDb(action, cartId, price)
          }
        } else {
          if (Number(inputVal.val()) > min) {
            changeQtyInDb(action, cartId, price)
          }
        }
      }

      function changeQtyInDb(action, cartId, price) {
        swal.showLoading()
        $.post(
          "<?= $SERVER_NAME ?>/backend/nodes?action=change_qty", {
            action: action,
            cart_id: cartId
          },
          (data, status) => {
            const resp = JSON.parse(data)
            if (resp.success) {
              swal.close()
              const inputVal = $(`#inputQuantity${cartId}`)

              if (action === "add") {
                inputVal.val(Number(inputVal.val()) + 1)
              } else {
                inputVal.val(Number(inputVal.val()) - 1)
              }
              $(`.medTotal${cartId}`).html(`₱${(Number(price) * Number(inputVal.val())).toFixed(2)}`)

              let number = 0;
              for (let i = 0; i < $(".toTotal").length; i++) {
                const toSum = Number($(".toTotal")[i].innerText.trim().replace("₱", ""))
                number += toSum
              }

              $("#overallTotal").html(`₱${(number).toFixed(2)}`)
            } else {
              swal.fire({
                title: 'Error!',
                html: resp.message,
                icon: 'error',
              })
            }

          }).fail(function(e) {
          swal.fire({
            title: 'Error!',
            text: e.statusText,
            icon: 'error',
          })
        });
      }

      $(document).ready(function() {
        $("#btnCheckOut").prop("disabled", $("#overallTotal")[0].innerText.replace("₱", "") === "0.00" ? true : false);

        const tableId = "#cartTable";
        var table = $(tableId).DataTable({
          paging: false,
          lengthChange: false,
          ordering: false,
          info: false,
          autoWidth: false,
          responsive: true,
          dom: 'lrt'
        });

        table.buttons().container()
          .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
      });
    </script>
</body>

</html>