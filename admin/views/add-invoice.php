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
                    <div class="card-body">
                      <table id="stockTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Price</th>
                            <th>Quantity</th>
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
                              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                              ig.price_id,
                              ig.quantity,
                              ig.expiration_date
                            FROM inventory_general ig
                            LEFT JOIN medicine_profile mp
                            ON mp.id = ig.medicine_id
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
                              <td class="align-middle price"><?= "₱ " . $price ?></td>
                              <td class="align-middle quantity"><?= $inventory->quantity ?></td>
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="align-middle">
                                <a href="javascript:void(0);" onclick="handleAddToOrder($(this), '<?= $inventory->quantity ?>')" class="h5 text-success m-2">
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
                    <div class="card-body">
                      <table id="orderTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Price</th>
                            <th>Quantity</th>
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
                          <button id="btnDiscount" class="btn btn-warning btn-block btn-sm m-2" style="height: 43px;">
                            Discount
                          </button>

                          <button id="btnClear" class="btn btn-danger btn-block btn-sm m-2 d-none" style="height: 43px;">
                            Clear Discount
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

    <?php include("../components/scripts.php") ?>
    <script>
      const tableId = "#stockTable";
      var stockTable = $(tableId).DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
          searchBuilder: {
            button: 'Filter',
          }
        },
        columnDefs: [{
          "targets": [5],
          "orderable": false
        }],
        // buttons: [{
        //   extend: 'searchBuilder',
        //   config: {
        //     columns: [0, 1, 2, 3, 4]
        //   }
        // }],
        // dom: 'Bfrtip',
      });

      const tableId2 = "#orderTable";
      var orderTable = $(tableId2).DataTable({
        paging: false,
        lengthChange: false,
        ordering: false,
        info: false,
        autoWidth: false,
        responsive: true,
        searching: false
      });

      stockTable.buttons().container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);

      orderTable.buttons().container()
        .appendTo(`${tableId2}_wrapper .col-md-6:eq(0)`);

      $("#btnDiscount").on("click", function() {
        $(this).addClass("d-none")
        $("#btnClear").removeClass("d-none")
      })

      $("#btnClear").on("click", function() {
        $(this).addClass("d-none")
        $("#btnDiscount").removeClass("d-none")
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
            quantity: $(elChild[3]).text(),
            orderTotal: Number($(elChild[4]).text().replace("₱", "").trim())
          }

          checkOutData.data.push(data)
        })

        checkOutData.subTotal = Number($("#subTotal").text().replace("₱", "").trim())
        checkOutData.discount = Number($("#discount").text().replace("₱", "").trim())
        checkOutData.total = Number($("#overallTotal").text().replace("₱", "").trim())

        console.log(checkOutData)
      })

      function removeRow(el, tableIndex, quantityToAdd) {
        const selectedStock = stockTable.rows(tableIndex).nodes()[0];
        const quantityEl = $(selectedStock).find(".quantity")
        const priceEl = $(selectedStock).find(".price")

        const newQuantity = Number(quantityEl.text().trim()) + Number(quantityToAdd)
        quantityEl.html(newQuantity)

        orderTable
          .row(el.parents('tr'))
          .remove()
          .draw();

        updateSubTotal()
      }

      function updateSubTotal() {
        const selectedOrder = orderTable.rows().nodes();
        let orderTableSubtotals = 0.00

        selectedOrder.each((e) => {
          const orderTableSubtotalsEl = $(e).children()[4];
          orderTableSubtotals += Number($(orderTableSubtotalsEl).text().replace("₱", "").trim());
        })

        $("#subTotal").html(`₱ ${orderTableSubtotals.toFixed(2)}`)

        updateOverAllTotal()
      }

      function updateOverAllTotal() {
        const subTotal = Number($("#subTotal").text().replace("₱", "").trim())
        const discount = Number($("#discount").text().replace("₱", "").trim())

        const overall = Number(subTotal) + Number(discount)
        $("#overallTotal").html(`₱ ${overall.toFixed(2)}`)
      }

      function handleAddToOrder(el, medQty) {
        swal.fire({
          title: "Add to order",
          html: "Quantity",
          input: 'number',
          showLoaderOnConfirm: true,
          confirmButtonText: 'Add',
          showCancelButton: true,
          preConfirm: (inputVal) => {
            if (Number(inputVal) > Number(medQty)) {
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
            const quantity = el.closest("tr").find(".quantity")

            const orderData = orderTable.rows().data().toArray();
            const orderDataIndex = orderData.findIndex((e) => e.some((s) => s === prodNumber.text().trim()))

            if (orderDataIndex == -1) {
              const quantityEl = JSON.stringify(el.closest("tr"))

              orderTable.row.add([
                prodNumber.html(),
                medicineName.html(),
                price.html(),
                res.value,
                `₱ ${(Number(res.value) * Number(price.text().trim().replace("₱", ""))).toFixed(2)}`,
                `
                <a href='javascript:void(0);' onclick="removeRow($(this), ${stockTable.row(el.closest("tr")).index()}, ${res.value})" class='h5 text-danger m-2'>
                  <i class='fa fa-times-circle' title='Remove' data-toggle='tooltip'></i>
                </a>
                `
              ]).draw();

              const newQuantity = Number(quantity.text().trim()) - Number(res.value)
              quantity.html(newQuantity)

            } else {
              const selectedOrder = orderTable.rows(orderDataIndex).nodes();
              const children = $(selectedOrder).children();

              const quantityEl = $(selectedOrder[0].childNodes[3]);
              const newQuantity = Number(quantityEl.text().trim()) + Number(res.value)

              quantityEl.html(newQuantity)

              const subTotalEl = $(selectedOrder[0].childNodes[4]);
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