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
                              <td class="align-middle quantity"><?= $inventory->quantity ?></td>
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="align-middle">
                                <a href="javascript:void(0);" onclick="handleAddToOrder($(this))" class="h5 text-success m-2">
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
                            <th>Item(s)</th>
                            <th>Total</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="tableOrderBody"></tbody>
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
                          <p class="h6">Sub total</p>
                        </div>
                        <div class="col-6 text-right">
                          <strong class="text-black" id="subTotal">
                            ₱ 0.00
                          </strong>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <p class="h6">Discount</p>
                        </div>
                        <div class="col-6 text-right">
                          <strong class="text-black" id="discount">
                            ₱ 0.00
                          </strong>
                        </div>
                      </div>
                      <hr>
                      <div class="row mb-5">
                        <div class="col-md-6">
                          <p class="h6">Total </p>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black" id="overallTotal">
                            ₱ 0.00
                          </strong>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 m-0">

                          <button id="btnClear" class="btn btn-danger btn-block btn-sm m-2 d-none" style="height: 43px;">
                            Clear Discount
                          </button>

                          <button id="btnDiscount" class="btn btn-warning btn-block btn-sm m-2" style="height: 43px;">
                            Add Discount
                          </button>

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
            <input type="text" name="productData" id="inputProductData" hidden>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Subtotal</label>
              <div class="col-sm-10">
                <input type="number" name="subTotal" id="inputSubTotal" step=".01" class="form-control" readonly required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Discount</label>
              <div class="col-sm-10">
                <input type="number" name="discount" id="inputDiscount" step=".01" class="form-control" readonly required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Total</label>
              <div class="col-sm-10">
                <input type="number" name="total" id="inputTotal" step=".01" class="form-control" readonly required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Amount </label>
              <div class="col-sm-10">
                <input type="number" name="amount" id="inputAmount" step=".01" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Change</label>
              <div class="col-sm-10">
                <input type="number" name="change" id="inputChange" step=".01" class="form-control" readonly required>
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


    $("#inputAmount").on("input", function() {
      // $("#inputChange").val("123")
      $("#inputChange").change()
      // console.log($(this).val())
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

    $("#btnCheckout").on("click", function() {

      const selectedOrder = orderTable.rows().nodes();
      let checkOutData = {
        subTotal: 0.00,
        discount: 0.00,
        total: 0.00,
        data: []
      }

      selectedOrder.each((e) => {
        const elChild = $(e).children();
        const data = {
          product_number: $(elChild[0]).text(),
          quantity: $(elChild[4]).text(),
          orderTotal: Number($(elChild[5]).text().replace("₱", "").trim())
        }

        checkOutData.data.push(data)
      })

      if (checkOutData.data.length > 0) {
        // $(this).prop("disabled", true)
        // $(this).html(
        //   `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
        //   <span>Loading...</span>`
        // )

        checkOutData.subTotal = Number($("#subTotal").text().replace("₱", "").trim())
        checkOutData.discount = Number($("#discount").text().replace("₱", "").trim())
        checkOutData.total = Number($("#overallTotal").text().replace("₱", "").trim())

        // console.log(checkOutData)

        $("#inputProductData").val(JSON.stringify(checkOutData))

        $("#inputSubTotal").val(checkOutData.subTotal.toFixed(2))
        $("#inputDiscount").val(checkOutData.discount.toFixed(2))
        $("#inputTotal").val(checkOutData.total.toFixed(2))

        $("#modalCheckOut").modal("show")
      }
    })

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

    function handleAddToOrder(el) {
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
          const prodNumber = el.closest("tr").find(".productNumber")
          const medicineName = el.closest("tr").find(".medicineName")
          const price = el.closest("tr").find(".price")
          const dosage = el.closest("tr").find(".dosage")

          const orderData = orderTable.rows().data().toArray();
          const orderDataIndex = orderData.findIndex((e) => e.some((s) => s === prodNumber.text().trim()))

          if (orderDataIndex == -1) {
            orderTable.row.add([
              prodNumber.html(),
              medicineName.html(),
              dosage.html(),
              price.html(),
              res.value,
              `₱ ${(Number(res.value) * Number(price.text().trim().replace("₱", ""))).toFixed(2)}`,
              `
                <a href='javascript:void(0);' onclick="removeRow($(this), ${stockTable.row(el.closest("tr")).index()})" class='h5 text-danger m-2'>
                  <i class='fa fa-times-circle' title='Remove' data-toggle='tooltip'></i>
                </a>
                `
            ]).draw();

            const newQuantity = `${Number(quantity.text().trim()) - Number(res.value)}`
            quantity.html(newQuantity)

          } else {
            const stockQuantity = el.closest("tr").find(".quantity")
            stockQuantity.html(`${Number(quantity.text().trim()) - Number(res.value)}`)

            const selectedOrder = orderTable.rows(orderDataIndex).nodes();
            const children = $(selectedOrder).children();

            const quantityEl = $(selectedOrder[0].childNodes[4]);
            const newQuantity = `${Number(quantityEl.text().trim()) + Number(res.value)}`

            quantityEl.html(newQuantity)

            const subTotalEl = $(selectedOrder[0].childNodes[5]);
            const newTotal = `₱ ${(Number(newQuantity) * Number(price.text().trim().replace("₱", ""))).toFixed(2)}`

            subTotalEl.html(newTotal)
          }

          updateSubTotal()
        }
      })
    }
  </script>
</body>

</html>