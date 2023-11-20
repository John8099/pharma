<?php include("./backend/nodes.php"); ?>
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
      <?php
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
        (SELECT category_name FROM category c WHERE c.id = mp.category_id) AS 'category'
        FROM inventory_general ig
        LEFT JOIN medicine_profile mp
        ON mp.id = ig.medicine_id
        WHERE ig.id = '$_GET[id]'
        "
      );

      if (mysqli_num_rows($inventoryQStr) > 0) :
        $inventory = mysqli_fetch_object($inventoryQStr);

      ?>
        <div class="container">
          <div class="row">
            <div class="col-md-5 mr-auto">
              <div class="border text-center">
                <img src="<?= getMedicineImage($inventory->medicine_id) ?>" alt="Image" class="img-fluid p-5">
              </div>
            </div>
            <div class="col-md-6">
              <h2 class="text-black">
                <?= $inventory->medicine_name ?>
              </h2>
              <p>
                <?= nl2br($inventory->description); ?>
              </p>

              <p>
                <!-- <del>$95.00</del> -->
                <strong class="text-primary h4">
                  <?= "₱ " . number_format($inventory->price, 2, '.', ',') ?>
                </strong>
              </p>
              <p>
                <strong class="text-dark h6">
                  <?= "($inventory->quantity) items left" ?>
                </strong>
              </p>

              <div class="mb-5">
                <div class="input-group mb-3" style="max-width: 220px;">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-primary js-btn-minus" type="button">
                      &minus;
                    </button>
                  </div>

                  <input type="number" class="form-control text-center" min="1" max="<?= $inventory->quantity ?>" value="1" id="inputQuantity">

                  <div class="input-group-append">
                    <button class="btn btn-outline-primary js-btn-plus" type="button">
                      &plus;
                    </button>
                  </div>
                </div>
              </div>

              <p>
                <button type="button" onclick="handleAddToCart('<?= $inventory->inventory_id ?>')" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary">
                  Add To Cart
                </button>
              </p>

              <div class="mt-5">
                <table class="table custom-table">
                  <caption style="caption-side:top" class="text-dark h4 bg-primary p-3 m-0">
                    Specification
                  </caption>
                  <tbody>
                    <tr>
                      <td>Brand name</td>
                      <td class="bg-light">
                        <?= $inventory->brand_name ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Generic name</td>
                      <td class="bg-light">
                        <?= $inventory->generic_name ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Dosage</td>
                      <td class="bg-light">
                        <?= $inventory->dosage ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Expiration date</td>
                      <td class="bg-light">
                        <?= date("M d, Y", strtotime($inventory->expiration_date)) ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <?php include("./components/scripts.php") ?>

</body>
<script>
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

  $("#inputQuantity").on("input", function(e) {
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

  function handleAddToCart(inventoryId) {
    const isLogin = "<?= json_encode($isLogin ? true : false) ?>";
    if (isLogin == "false") {
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
    } else {
      swal.close()
      swal.showLoading()
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=add_to_cart", {
          inventory_id: inventoryId,
          quantity: $("#inputQuantity").val()
        },
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            text: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => resp.success ? window.location.href = './store' : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }
  }
</script>

</html>