<?php
include("../../backend/nodes.php");
include("../components/modals.php");

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

                <div class="col-12">
                  <div class="card">
                    <div class="card-body table-border-style">
                      <table id="returnedTable" class="table table-hover table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>New Product #</th>
                            <th>Medicine </small></th>
                            <th>Dosage</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Expiration</th>
                            <th>Returned</th>
                            <th>Replaced</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $returnData = getTableData("returned");

                          foreach ($returnData as $returned) :
                            $query = mysqli_query(
                              $conn,
                              "SELECT 
                                ig.id AS 'inventory_id',
                                ig.medicine_id,
                                ig.product_number,
                                mp.medicine_name,
                                mp.generic_name,
                                mp.dosage,
                                (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                                ig.price_id,
                                (SELECT supplier_name FROM supplier s WHERE s.id = ig.supplier_id) AS 'supplier_name',
                                ig.quantity,
                                ig.date_received,
                                ig.expiration_date,
                                ig.serial_number
                              FROM inventory_general ig
                              LEFT JOIN medicine_profile mp
                              ON mp.id = ig.medicine_id
                              WHERE ig.is_returned = '1' and ig.id = '$returned->inventory_id'
                                "
                            );
                            $inventory = mysqli_fetch_object($query);
                            $priceData = getTableData("price", "id", $inventory->price_id);
                            $price = count($priceData) > 0 ? $priceData[0]->price : "NULL";

                            $imgSrc = getMedicineImage($inventory->medicine_id);
                            $exploded =  explode("/", $imgSrc);
                            $alt = $exploded[count($exploded) - 1];

                          ?>
                            <tr>
                              <td class="align-middle"><?= $inventory->product_number ?></td>
                              <td class="allign-middle"><?= $returned->product_number ? $returned->product_number : "<em>N/A</em>" ?></td>
                              <td class="align-middle">
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage<?= $inventory->inventory_id ?>')">
                                  <?= $inventory->generic_name ?>
                                </button>
                              </td>
                              <td class="align-middle dosage"><?= $inventory->dosage  ?></td>
                              <td class="align-middle"><?= number_format($price, 2, '.', ',') ?></td>
                              <td class="align-middle"><?= $inventory->supplier_name ?></td>
                              <td class="align-middle"><?= $inventory->quantity ?></td>
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="allign-middle"><?= date("Y-m-d", strtotime($returned->date_returned)) ?></td>
                              <td class="allign-middle"><?= $returned->date_replaced ? date("Y-m-d", strtotime($returned->date_replaced)) : "<em>N/A</em>" ?></td>
                              <td class="align-middle">
                                <?php if (!$returned->date_replaced && !$returned->product_number) : ?>
                                  <a href="javascript:void()" onclick="handleAddToStocks('<?= $returned->id ?>', '<?= $returned->inventory_id ?>')" class="h5 text-success m-2">
                                    <i class="fa fa-plus-circle" title="Add to Stocks" data-toggle="tooltip"></i>
                                  </a>
                                <?php else : ?>
                                  <em class="text-muted">N/A</em>
                                <?php endif; ?>
                              </td>
                            </tr>
                            <div id='divModalImage<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                              <span class='close' onclick='handleClose(`divModalImage<?= $inventory->inventory_id ?>`)'>&times;</span>
                              <img class='div-modal-content' src="<?= $imgSrc  ?>">
                              <div id="imgCaption"><?= $alt ?></div>
                            </div>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- product profit end -->
              </div>

              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= addStockWithId(true) ?>

  <?php include("../components/scripts.php") ?>
  <script>
    function handleAddToStocks(returnedId, inventoryId) {
      $.get(
        `<?= $SERVER_NAME ?>/backend/nodes?action=get_stocks&&returned_id=${returnedId}&&inventory_id=${inventoryId}`,
        (data, status) => {
          const resp = JSON.parse(data)

          const formFieldNames = [{
              "id": "#returnedId",
              "respField": "return_id"
            },
            {
              "id": "#medicineId",
              "respField": "medicine_id"
            },
            {
              "id": "#medicineName",
              "respField": "medicine_name"
            },
            {
              "id": "#supplierId",
              "respField": "supplier_id"
            },
            {
              "id": "#supplierName",
              "respField": "supplier_name"
            },
            {
              "id": "#purchasePrice",
              "respField": "purchase_price"
            },
            {
              "id": "#markUp",
              "respField": "mark_up"
            },
            {
              "id": "#price",
              "respField": "price"
            },
            {
              "id": "#quantity",
              "respField": "quantity"
            },
            {
              "id": "#receiveDate",
              "respField": "date_received"
            },


          ];

          for (const formVal of formFieldNames) {
            const index = Object.entries(resp.data).findIndex(object => {
              return object[0] === formVal.respField;
            });
            $(formVal.id).val(Object.entries(resp.data)[index][1])
          }
          $("#checkIsVatable").prop("checked", resp.data.is_vatable);
          $("#checkIsDiscountable").prop("checked", resp.data.is_discountable);

          $("#addStock").modal("show");

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }

    $("#addStockForm").on("submit", function(e) {
      e.preventDefault();

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_stock",
        $(this).serialize(),
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
    })
    $(document).ready(function() {
      const tableId = "#returnedTable";
      var table = $(tableId).DataTable({
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
          "targets": [9],
          "orderable": false
        }],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
          }
        }],
        dom: 'Bfrtip',
      });

      table.buttons().container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
    });
  </script>
</body>

</html>