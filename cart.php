<?php
include("./backend/nodes.php");
if (!$isLogin) {
  header("location: ./");
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>
<style>
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }
</style>

<body>

  <div class="site-wrap">

    <?php include("./components/header-nav.php") ?>

    <div class="site-section">
      <div class="container-fluid">
        <div class="row mb-5">
          <form class="col-md-12" method="POST" id="form-update-cart">
            <div class="site-blocks-table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-price">Dosage</th>
                    <th class="product-price">Price</th>
                    <th class="product-quantity">Item(s)</th>
                    <th class="product-total">Total</th>
                    <th class="product-remove">Remove</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $hasPrescriptionRequired = "false";
                  $overAllTotal = 0.00;
                  $discountedTotal = 0.00;
                  $cartData = [];
                  if ($user) :
                    $cartData = getTableWithWhere("cart", "user_id ='$user->id' and status='pending' and checkout_date IS NULL");

                    if (count($cartData) > 0) :
                      foreach ($cartData as $cart) :
                        $inventoryQStr = mysqli_query(
                          $conn,
                          "SELECT 
                          ig.id AS 'inventory_id',
                          ig.medicine_id,
                          mp.medicine_name,
                          mp.generic_name,
                          ig.product_number,
                          mp.description,
                          mp.dosage,
                          ig.quantity,
                          ig.expiration_date,
                          (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                          (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price',
                          (SELECT category_name FROM category c WHERE c.id = mp.category_id) AS 'category',
                          (SELECT prescription_required FROM category c WHERE c.id = mp.category_id) AS 'prescription_required'
                          FROM inventory_general ig
                          LEFT JOIN medicine_profile mp
                          ON mp.id = ig.medicine_id
                          WHERE ig.id = '$cart->inventory_id'
                          "
                        );
                        $inventory = mysqli_fetch_object($inventoryQStr);
                        $overAllTotal += (intval($inventory->price) * intval($cart->quantity));
                        $discountedTotal += getDiscounted($inventory->inventory_id, $inventory->price) * intval($cart->quantity);

                        if ($hasPrescriptionRequired == "false") {
                          if ($inventory->prescription_required == "1") {
                            $hasPrescriptionRequired = "true";
                          }
                        }
                  ?>
                        <tr>
                          <td class="product-thumbnail">
                            <img src="<?= getMedicineImage($inventory->medicine_id) ?>" alt="Image" class="img-fluid">
                          </td>
                          <td class="product-name">
                            <h2 class="h5 text-black"><?= $inventory->medicine_name ?></h2>
                          </td>
                          <td><?= $inventory->dosage ?></td>
                          <td class="text-left">
                            Regular: <?= "₱ " . number_format($inventory->price, 2, '.', ',') ?> <br>
                            Discounted: <?= "₱ " . getDiscounted($inventory->inventory_id, $inventory->price) ?>
                          </td>
                          <td>
                            <input type="text" class="cart_id" value="<?= $cart->id ?>" hidden readonly>
                            <div class="input-group mb-3 m-auto" style="max-width: 120px;">
                              <div class="input-group-prepend">
                                <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                              </div>
                              <input type="number" class="form-control text-center inputQuantity" min="1" max="<?= $inventory->quantity ?>" value="<?= $cart->quantity ?>" name="cartQty<?= $cart->id ?>">
                              <div class="input-group-append">
                                <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                              </div>
                            </div>
                          </td>
                          <td class="text-left prices">
                            Regular:
                            <span class="regular">
                              <?= "₱ " . number_format((intval($inventory->price) * intval($cart->quantity)), 2, '.', ',') ?>
                            </span>
                            <br>
                            Discounted:
                            <span class="discounted">
                              <?= "₱ " . number_format((getDiscounted($inventory->inventory_id, $inventory->price) * intval($cart->quantity)), 2, '.', ',') ?>
                            </span>
                          </td>
                          <td>
                            <button type="button" onclick="return deleteData('cart', 'id', '<?= $cart->id ?>')" class="btn btn-primary height-auto btn-sm">
                              X
                            </button>
                          </td>
                        </tr>
                    <?php endforeach;
                    endif;
                    ?>
                  <?php endif; ?>

                </tbody>
              </table>
            </div>
            <?php $isDisabled = count($cartData) == 0 ? "disabled" : ""; ?>
            <div class="row">
              <div class="col-md-6">
                <div class="row mb-5">
                  <!-- <div class="col-md-6 mb-3 mb-md-0">
                    <button type="submit" class="btn btn-primary btn-md btn-block" <?= $isDisabled ?>>Update Cart</button>
                  </div> -->
                  <div class="col-md-6">
                    <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location.href='<?= $SERVER_NAME ?>/store'">Continue Shopping</button>
                  </div>
                </div>

              </div>
              <div class="col-md-6 pl-5">
                <div class="row justify-content-end">
                  <div class="col-md-7">
                    <div class="row">
                      <div class="col-md-12 text-right border-bottom mb-5">
                        <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <span class="text-black">Regular Total</span>
                      </div>
                      <div class="col-md-6 text-right">
                        <strong class="text-black">
                          <span id="regularTotal">
                            <?= "₱ " . number_format($overAllTotal, 2, '.', ',') ?>
                          </span>
                        </strong>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <span class="text-black">Discounted Total</span>
                      </div>
                      <div class="col-md-6 text-right">
                        <strong class="text-black">
                          <span id="discountedTotal">
                            <?= "₱ " . number_format($discountedTotal, 2, '.', ',') ?>
                          </span>
                        </strong>
                      </div>
                    </div>

                    <div class="row mt-5">
                      <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="handleCheckout('<?= $hasPrescriptionRequired ?>')" <?= $isDisabled ?>>
                          Checkout
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
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
                  <img src="./public/prescription.png" class="rounded mx-auto d-block" style="width: 150px; height: 150px;" id="display">
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

  function handleCheckout(isPrescriptionRequired) {
    if (isPrescriptionRequired === "true") {
      $("#uploadPrescription").modal("show")
      console.log(isPrescriptionRequired)
    } else {
      swal.showLoading()
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=checkout",
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
    swal.showLoading()
    const inputNum = $(this).closest(".input-group").find(".form-control");
    const inputVal = inputNum.val();
    const minVal = inputNum[0].min;

    let newVal = 0;

    if (inputVal != minVal) {
      inputNum.val(parseInt(inputVal) - 1)
      newVal = inputNum.val()
    } else {
      inputNum.val(parseInt(minVal))
      newVal = inputNum.val()
    }

    updatePrices($(this), newVal)
  });

  $(".js-btn-plus").on("click", function(e) {
    e.preventDefault();
    swal.showLoading()
    const inputNum = $(this).closest(".input-group").find(".form-control");
    const inputVal = inputNum.val();
    const maxVal = inputNum[0].max;

    let newVal = 0;

    if (inputVal != maxVal) {
      inputNum.val(parseInt(inputVal) + 1)
      newVal = inputNum.val()
    } else {
      inputNum.val(parseInt(maxVal))
      newVal = inputNum.val()
    }

    updatePrices($(this), newVal)
  });

  $(".inputQuantity").on("input", function(e) {
    swal.showLoading()
    const inputVal = $(this).val()
    const maxVal = $(this)[0].max;
    const minVal = $(this)[0].min;

    let newVal = 0;

    if (Number(maxVal) < Number(inputVal)) {
      $(this).val(parseInt(maxVal))
      newVal = parseInt(maxVal)
    }

    if (Number(minVal) > Number(inputVal)) {
      $(this).val(parseInt(minVal))
      newVal = parseInt(minVal)
    }

    updatePrices($(this), newVal)
  })

  function updatePrices(el, quantity) {
    const pricesEl = el.parents().siblings().closest(".prices");
    const cartId = el.parents().siblings().closest(".cart_id");

    const regularTotalEl = $("#regularTotal")
    const discountedTotalEl = $("#discountedTotal")

    const regular = pricesEl.find(".regular");
    const discounted = pricesEl.find(".discounted");

    console.log(quantity)
    $.post(
      "<?= $SERVER_NAME ?>/backend/nodes?action=update_cart", {
        cart_id: cartId.val(),
        quantity: quantity
      },
      (data, status) => {
        const resp = JSON.parse(data)
        if (!resp.success) {
          swal.fire({
            title: 'Error!',
            text: resp.message,
            icon: 'error',
          }).then(() => !resp.success ? window.location.reload() : undefined)
        } else {
          window.location.reload()
        }


      }).fail(function(e) {
      swal.fire({
        title: 'Error!',
        text: e.statusText,
        icon: 'error',
      })
    });

    swal.close()
  }
</script>

</html>