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
                  <div class="card">
                    <div class="card-header p-2 ml-2 mt-2">
                      <h3 class="card-title">
                        Stocks
                        <button onclick="return window.location.replace('<?= $SERVER_NAME ?>/admin/views/invoice')" type="button" class="btn btn-secondary btn-sm float-right">
                          Go Back
                        </button>
                      </h3>
                    </div>
                    <div class="card-body" style="overflow-x: scroll;">
                      <table id="stockTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Dosage</th>
                            <th>Price</th>
                            <th>Discounted</th>
                            <th>Item(s)</th>
                            <th>Expiration</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = mysqli_query(
                            $conn,
                            "SELECT 
                              ig.id AS 'inventory_id',
                              ig.medicine_id,
                              mp.medicine_name,
                              mp.generic_name,
                              ig.product_number,
                              mp.dosage,
                              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                              ig.price_id,
                              ig.quantity,
                              ig.expiration_date
                            FROM inventory_general ig
                            LEFT JOIN medicine_profile mp
                            ON mp.id = ig.medicine_id
                            WHERE ig.is_returned <> '1'
                              "
                          );
                          while ($inventory = mysqli_fetch_object($query)) :
                            $priceData = getTableData("price", "id", $inventory->price_id);
                            $price = count($priceData) > 0 ? $priceData[0]->price : "NULL";

                            $imgSrc = getMedicineImage($inventory->medicine_id);
                            $exploded =  explode("/", $imgSrc);
                            $alt = $exploded[count($exploded) - 1];
                          ?>
                            <tr>
                              <td class="align-middle productNumber"><?= $inventory->product_number ?></td>
                              <td class="align-middle medicineName">
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage')">
                                  <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?>
                                </button>
                              </td>
                              <td class="align-middle dosage"><?= $inventory->dosage ?></td>
                              <td class="align-middle price"><?= $price ?></td>
                              <td class="align-middle price"><?= getDiscounted($inventory->inventory_id, $price) ?></td>
                              <td class="align-middle quantity"><?= $inventory->quantity ?></td>
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="align-middle">
                                <a href="javascript:void(0);" onclick="handleAddToOrder($(this), '<?= $inventory->inventory_id ?>', '<?= $priceData[0]->price ?>')" class="h5 text-success m-2">
                                  <i class="fa fa-plus-circle" title="Add to Order" data-toggle="tooltip"></i>
                                </a>
                              </td>
                            </tr>
                            <div id='divModalImage' class='div-modal pt-5'>
                              <span class='close' onclick='handleClose(`divModalImage`)'>&times;</span>
                              <img class='div-modal-content' src="<?= $imgSrc  ?>">
                              <div id="imgCaption"><?= $alt ?></div>
                            </div>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header p-2 ml-2 mt-2">
                      <h3 class="card-title">
                        Orders Information
                      </h3>
                    </div>
                    <div class="card-body" style="overflow-x: scroll;">
                      <table id="orderTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Dosage</th>
                            <th>Price</th>
                            <th>Discounted</th>
                            <th>Item(s)</th>
                            <th>Total</th>
                            <th>Discounted Total</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $orderTblQuery = mysqli_query(
                            $conn,
                            "SELECT 
                              od.inventory_general_id,
                              od.quantity,
                              od.order_subtotal,
                              o.overall_total,
                              od.id AS 'order_details_id',
                              o.id AS 'order_id'
                              FROM order_tbl o 
                              LEFT JOIN order_details od 
                              ON o.id=od.order_id 
                              WHERE o.user_id=$_SESSION[userId] and `type`='walk_in' and overall_total IS NULL "
                          );
                          $overAllTotal = 0.00;
                          $overAllDiscountedTotal = 0.00;

                          while ($order = mysqli_fetch_object($orderTblQuery)) :
                            $inventory = getSingleDataWithWhere("inventory_general", "id='$order->inventory_general_id'");
                            $medicine = getSingleDataWithWhere("medicine_profile", "id='$inventory->medicine_id'");
                            $brand = getSingleDataWithWhere("brands", "id='$medicine->brand_id'");
                            $price = getSingleDataWithWhere("price", "id='$inventory->price_id'");

                            $overAllTotal += doubleval($order->order_subtotal);

                            $discountedPrice = getDiscounted($inventory->id, $price->price);
                            $totalDiscount = doubleval($discountedPrice) * intval($order->quantity);
                            $overAllDiscountedTotal += $totalDiscount;
                          ?>
                            <tr>
                              <td><?= $inventory->product_number ?></td>
                              <td>
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('<?= $inventory->product_number ?>')">
                                  <?= "$medicine->medicine_name/ $brand->brand_name/ $medicine->generic_name" ?>
                                </button>
                              </td>
                              <td> <?= $medicine->dosage ?></td>
                              <td> <?= $price->price ?></td>
                              <td><?= $discountedPrice ?></td>
                              <td> <?= $order->quantity ?></td>
                              <td> <?= $order->order_subtotal ?></td>
                              <td> <?= number_format($totalDiscount, 2, ".", ",") ?></td>
                              <td>
                                <a href="javascript:void(0);" onclick="handleRemoveOrder('<?= $order->inventory_general_id ?>', '<?= $order->order_details_id ?>', '<?= $order->order_id ?>', '<?= $order->quantity  ?>')" class="h5 text-danger m-2">
                                  <i class="fa fa-times-circle" title="Remove from order" data-toggle="tooltip"></i>
                                </a>
                              </td>
                              <div id='<?= $inventory->product_number ?>' class='div-modal pt-5'>
                                <span class='close' onclick='handleClose(`<?= $inventory->product_number ?>`)'>&times;</span>
                                <img class='div-modal-content' src="<?= $imgSrc  ?>">
                                <div id="imgCaption"><?= $alt ?></div>
                              </div>
                            </tr>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-end">
                <div class="col-md-5 col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-6">
                          <p class="h6">Regular Total</p>
                        </div>
                        <div class="col-6 text-right">
                          <strong class="text-black" id="subTotal">
                            <?= "₱ " . ($overAllTotal == 0.00 ? "0.00" : number_format($overAllTotal, 2, ".", ",")) ?>
                          </strong>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <p class="h6">Discounted Total</p>
                        </div>
                        <div class="col-6 text-right">
                          <strong class="text-black" id="discount">
                            <?= "₱ " . ($overAllDiscountedTotal == 0.00 ? "0.00" : number_format($overAllDiscountedTotal, 2, ".", ",")) ?>
                          </strong>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 m-0">

                          <!-- <button id="btnClear" class="btn btn-danger btn-block btn-sm m-2 d-none" style="height: 43px;">
                            Clear Discount
                          </button>

                          <button id="btnDiscount" class="btn btn-warning btn-block btn-sm m-2" style="height: 43px;">
                            Add Discount
                          </button> -->

                        </div>
                        <div class="col-md-6 m-0">
                          <button id="btnCheckout" class="btn btn-primary btn-block btn-sm m-2" style="height: 43px;" id="btnCheckOut">
                            Checkout
                          </button>
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
                <input type="number" name="total" id="inputTotal" step=".01" class="form-control" readonly required>
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
            <button type="submit" class="btn btn-primary">Proceed</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    function handleRemoveOrder(inventory_id, order_details_id, order_id, quantity) {
      swal.fire({
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
              `<?= $SERVER_NAME ?>/backend/nodes.php?action=remove_walk_in_order`, {
                inventory_id: inventory_id,
                order_details_id: order_details_id,
                order_id: order_id,
                quantity: quantity
              },
              (data, status) => {
                const resp = JSON.parse(data)
                if (!resp.success) {
                  swal.fire({
                    title: 'Error!',
                    html: resp.message,
                    icon: 'error',
                  }).then(() => resp.success ? window.location.reload() : undefined)
                } else {
                  window.location.reload()
                }
              }
            ).fail(function(e) {
              swal.fire({
                title: "Error!",
                html: e.statusText,
                icon: "error",
              });
            });
          }
        });
    }

    function handleAddToOrder(el, inventoryId, price) {
      const quantity = el.closest("tr").find(".quantity")

      swal.fire({
        title: "Add to order",
        html: "Quantity",
        input: 'number',
        showLoaderOnConfirm: true,
        confirmButtonText: 'Add',
        showCancelButton: true,
        preConfirm: (inputVal) => {
          if (inputVal === "") {
            return Swal.showValidationMessage("Please input Quantity.")
          }
          if (inputVal === "0") {
            return Swal.showValidationMessage("Zero is not accepted.")
          }
          if (Number(inputVal) > Number(quantity.text().trim())) {
            return Swal.showValidationMessage("Quantity should not be greater than the current quantity.")
          }
          return inputVal
        },
        allowOutsideClick: false
      }).then((res) => {
        if (res.isConfirmed) {
          swal.showLoading();
          $.post(
            "<?= $SERVER_NAME ?>/backend/nodes?action=add_walk_in_order", {
              inventory_id: inventoryId,
              quantity: res.value,
              price: price
            },
            (data, status) => {
              const resp = JSON.parse(data)
              if (!resp.success) {
                swal.fire({
                  title: 'Error!',
                  html: resp.message,
                  icon: 'error',
                }).then(() => resp.success ? window.location.reload() : undefined)
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
        }
      })
    }

    $("#btnCheckout").on("click", function() {
      const subTotal = $("#subTotal").text().replace("₱ ", "").trim();


      $("#inputTotal").val(subTotal)

      $("#modalCheckOut").modal("show")
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

    $("#checkoutForm").on("submit", function(e) {
      e.preventDefault()
      swal.showLoading()

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_checkout",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => resp.success ? window.location.href = `print-receipt?id=${resp.invoice_id}` : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    })

    function handleTriggerChange() {
      $("#inputChange").change()
    }

    $("#inputAmount").on("input", function() {
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
      }
    })

    let isDiscounted = false;

    const tableId = "#stockTable";
    var stockTable = $(tableId).DataTable({
      paging: true,
      lengthChange: false,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      language: {
        searchBuilder: {
          button: 'Filter',
        }
      },
      columnDefs: [{
        "targets": [6],
        "orderable": false
      }]
    });

    const tableId2 = "#orderTable";
    var orderTable = $(tableId2).DataTable({
      paging: false,
      lengthChange: false,
      ordering: false,
      info: false,
      autoWidth: false,
      responsive: false,
      searching: false
    });

    stockTable.buttons().container()
      .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);

    orderTable.buttons().container()
      .appendTo(`${tableId2}_wrapper .col-md-6:eq(0)`);

    $("#btnDiscount").on("click", function() {
      $(this).addClass("d-none")
      $("#btnClear").removeClass("d-none")

      isDiscounted = true
      updateDiscount()
      updateOverAllTotal()
    })

    $("#btnClear").on("click", function() {
      $(this).addClass("d-none")
      $("#btnDiscount").removeClass("d-none")

      isDiscounted = false
      $("#discount").html(`₱ 0.00`)
      updateOverAllTotal()
    })

    function updateDiscount() {
      const subTotal = Number($("#subTotal").text().replace("₱", "").trim());

      const discountPercent = 0.20; // 20%

      const newDiscount = (subTotal * discountPercent)

      $("#discount").html(`₱ ${newDiscount.toFixed(2)}`)
    }

    function removeRow(el, tableIndex) {
      const selectedStock = stockTable.rows(tableIndex).nodes()[0];
      const quantityEl = $(selectedStock).find(".quantity")
      const priceEl = $(selectedStock).find(".price")

      const orderSelectedRowIndex = orderTable.row(el.closest("tr")).index();
      const orderData = orderTable.rows(orderSelectedRowIndex).nodes();
      const children = $(orderData).children();

      const orderQuantityEl = $(orderData[0].childNodes[4]);

      const newQuantity = Number(quantityEl.text().trim()) + Number(orderQuantityEl.text().trim())
      quantityEl.html(newQuantity)

      orderTable
        .row(el.parents('tr'))
        .remove()
        .draw();

      updateSubTotal()
      updateDiscount()
    }

    function updateSubTotal() {
      const selectedOrder = orderTable.rows().nodes();
      let orderTableSubtotals = 0.00

      selectedOrder.each((e) => {
        const orderTableSubtotalsEl = $(e).children()[5];
        orderTableSubtotals += Number($(orderTableSubtotalsEl).text().replace("₱", "").trim());
      })

      $("#subTotal").html(`₱ ${orderTableSubtotals.toFixed(2)}`)
      if (isDiscounted) {
        updateDiscount()
      }
      updateOverAllTotal()
    }

    function updateOverAllTotal() {
      const subTotal = Number($("#subTotal").text().replace("₱", "").trim())
      const discount = Number($("#discount").text().replace("₱", "").trim())

      const overall = Number(subTotal) - Number(discount)
      $("#overallTotal").html(`₱ ${overall.toFixed(2)}`)
    }
  </script>
</body>

</html>